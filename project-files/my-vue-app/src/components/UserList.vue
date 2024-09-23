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
import { getUsers, createUser } from '../seevices/api';

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
