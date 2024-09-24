<template>
  <div id="app">
      <nav>
          <router-link to="/">User List</router-link> <br>
          <router-link to="/hello">HEllo World</router-link>
          <!-- 可以在此处添加其他链接 -->
      </nav>
      <router-view />
  </div>
</template>
