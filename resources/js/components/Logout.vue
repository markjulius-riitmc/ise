<template>
    <div>

    </div>
</template>

<script>
    export default {
        created() {
            EventBus.$emit('logout')
        }
    }
</script>

<style lang="scss" scoped>

</style>