<script>
    // var LoadingLayer = document.createElement('div');
    
    export default {
        name: 'LoadingLayer',
        props: ['loading'],
        mounted() {
            
            console.log('Component `LoadingLayer` mounted.');
        },
        methods: {
            show () {
                alert('Привет');
            }
        }
    }
</script>

<style>
    
</style>