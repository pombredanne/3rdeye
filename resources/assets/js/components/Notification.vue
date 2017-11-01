<template>
    <div></div>
</template>
<script>
    export default{
        mounted(){
            this.listen()
        },
        methods: {
            listen(){
                Echo.public()
                .notification((notification) =>{
                    alert('new notification')
                    console.log(notification)
                })
            }
        }
    }
</script>