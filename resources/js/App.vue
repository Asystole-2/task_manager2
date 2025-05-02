<template>
    <div class="app-container">
        <nav>
            <Link href="/">Home</Link>
            <Link href="/boards">Boards</Link>
        </nav>

        <slot />
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style>
.app-container {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

nav {
    padding: 20px;
    background: #f8f9fa;
    margin-bottom: 20px;
}

nav a {
    margin-right: 15px;
    text-decoration: none;
    color: #333;
}

nav a.router-link-exact-active {
    color: #4a90e2;
    font-weight: bold;
}
</style>
