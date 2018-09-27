<script>
    export default {
        props: ['tabs', 'selected'],

        methods: {
            addTab() {
                this.$root.addTab();
            },

            activateTab(id) {
                this.$root.activateTab(id);
            },

            deleteTab(id) {
                this.$root.deleteTab(id);
            }
        }
    }
</script>
