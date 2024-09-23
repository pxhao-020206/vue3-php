> ## 前言
> 实现前后端交互，涉及到的技术栈及软件： *VUE3*、*php7*、*axios*、*MySQL*、*Navicate lite*、*VScode*、*postman*.

# 开始


# 环境搭建

## 创建vue项目
 __WIN+R__  `cmd`
 进入想创建项目的路径  ~~这里用桌面为例~~：
 ```cmd
cd Desktop
npm create vue
//创建完毕后
cd my-vue-app
 ```

## 在vue项目中安装Axios
 如题，继续在控制台中安装Axios
 `npm install axios`

## 启动开发服务器
`npm run serve`


# 创建数据库和数据表

## Navicat
+ 打开软件
+ 新建连接
+ 进入连接
+ 新建数据库`my_database`并进入
+ 新建表 "users"
+ 编辑表结构，实例：
    + id int(4) 非空，自增 主键
    + username VARCHAR(100)
    + password VARCHAR(100)
    + email VARCHAR(100)
+ 表创建完毕后新增表内容，依次输入 "test_username","123456","example@qq.com"


# 搭建php后端

+ 进入php服务器的根目录（因为这里使用的是phpstudy 搭建的WNMP，只需找到phpstudy安装路径下的WWW文件夹即可）
+ 创建新文件夹"my_php_api"
+ 进入，创建"db.php"，对数据库进行连接配置：
  ```php
  <?php
  $host = 'localhost';  // 数据库主机
  $db   = 'my_database'; // 使用的数据库名
  $user = 'root';        // 数据库用户名
  $pass = '';            // 数据库密码
  $charset = 'utf8mb4';

  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try {
    $pdo = new PDO($dsn, $user, $pass, $options);
  } catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }
  ?>
  ```
+ 创建用户的API 文件： 新建"users.php"文件，输入内容：
   ```php
  <?php
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');  // 允许任何来源
  header('Access-Control-Allow-Methods: GET, POST');  // 允许 GET 和 POST 方法
  header('Access-Control-Allow-Headers: Content-Type');  // 允许特定的请求头
  require 'db.php';

  // 获取用户列表
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll();
    echo json_encode($users);
  }

  // 添加新用户
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username;
    $password = password_hash($data->password, PASSWORD_DEFAULT); // 密码加密
    $email = $data->email;

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $email]);

    echo json_encode(['message' => 'User created successfully']);
  }
  ?>
  ```
+ 运行 WNMP 测试php api：进入[http://localhost/my_php_api/users.php](http://localhost/my_php_api/users.php) 以能看到数据库中的数据为准
+ 使用postman ，测试"用户添加"的功能


# 在VUE 前端中调用php API
+ 在 Vue 项目中的 src 文件夹下，创建一个新的文件夹，命名为 services，在其中创建一个文件 api.js，用于管理与后端 API 的请求。
+ 添加内容：
  ```php
  import axios from 'axios';

  const apiUrl = 'http://localhost/my_php_api/users.php'; // API 地址

  export const getUsers = async () => {
    try {
        const response = await axios.get(apiUrl);
        return response.data;
    } catch (error) {
        console.error('Error fetching users:', error);
        throw error;
    }
  };

  export const createUser = async (user) => {
    try {
        const response = await axios.post(apiUrl, user);
        return response.data;
    } catch (error) {
        console.error('Error creating user:', error);
        throw error;
    }
  };
  ```
## 创建vue组件
在src/components路径下新建组件"UserList.vue",进入并编辑一个简单的组件实现用户添加：
```vue
    <template>
    <div>
        <h1>User List</h1>
        <ul>
            <li v-for="user in users" :key="user.id">
                {{ user.username }} - {{ user.email }}
            </li>
        </ul>
        <h2>Add User</h2>
        <input v-model="newUser.username" placeholder="Username" />
        <input v-model="newUser.password" type="password" placeholder="Password" />
        <input v-model="newUser.email" placeholder="Email" />
        <button @click="addUser">Add User</button>
    </div>
</template>

<script>
import { getUsers, createUser } from '../services/api';

export default {
    data() {
        return {
            users: [],
            newUser: {
                username: '',
                password: '',
                email: ''
            }
        };
    },
    async created() {
        await this.fetchUsers();
    },
    methods: {
        async fetchUsers() {
            this.users = await getUsers();
        },
        async addUser() {
            try {
                await createUser(this.newUser);
                await this.fetchUsers();
                this.newUser.username = '';
                this.newUser.password = '';
                this.newUser.email = '';
            } catch (error) {
                console.error('Error adding user:', error);
            }
        }
    }
};
</script>

```
## 在APP中使用组件
打开src/APP.vue 编辑使用该组件：
```vue
  <template>
    <div id="app">
        <UserList />
    </div>
</template>

<script>
import UserList from './components/UserList.vue';

export default {
    components: {
        UserList
    }
};
</script>
```
## 运行 npm run serve

# OK! 可以在前端VUE页面中编辑新增用户数据，然后数据会传到数据库中保存，二次进入该地址后，呈现内容也不会刷新
