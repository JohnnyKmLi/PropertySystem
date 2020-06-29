new Vue({
    el: '#propertiesDiv',
    data: {
        properties: {},
        test: 'test',
    },
    methods: {
        refreshProperties: function() {
            axios.get('http://localhost/?url=sync')
        },
        showProperties: function() {
            axios.get('http://localhost/?url=read')
            .then(response=> {
                this.properties = response.data;
            })
        }
    }
});