<script>
    export default {
        props: ['tabs', 'selected'],

        methods: {
            activateTab(id) {
                this.$root.activateTab(id);
            },

            updateTabName(id, name) {
                console.log(id, name);
            },
        }
    }
</script>
