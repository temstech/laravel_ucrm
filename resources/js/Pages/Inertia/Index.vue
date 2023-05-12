<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import FlashMessage from "@/Components/FlashMessage.vue";

defineProps({
    blogs: Array,
})
</script>

<template>
<FlashMessage />
<ul>
    <li v-for="blog in blogs" :key="blog.id">
        件名：<Link class="text-blue-400" :href="route('inertia.show',{ id: blog.id })">{{ blog.title }}</Link>
        本文：{{ blog.content }}
    </li>
</ul>
</template>

