<div id="app" class="jungle_email_infos_setup">
    在线人数统计

</div>
<script>
    const app = Vue.createApp({
        data() {
            return {
                queryForm: {
                    order: 'ASC',
                    orderby: "menu_order",
                    data: {},
                },
                columns: {
                    name: {
                        label: "名称",
                    },
                    email: {
                        label: "邮箱",
                    },
                    phone: {
                        label: "手机",
                    },
                    ip_address: {
                        label: "ip",
                    },
                    country: {
                        label: "国家",
                    },
                    countryCode: {
                        label: "国家代码",
                    },
                    state: {
                        label: "州/省",
                    },
                    city: {
                        label: "城市",
                    },
                    device: {
                        label: "设备",
                    },
                    browser: {
                        label: "浏览器",
                    },
                    send_time: {
                        label: "发送时间",
                    },
                    send_count: {
                        label: "发送次数",
                    },
                    status: {
                        label: "状态",
                    },
                    remark: {
                        label: "备注",
                    },
                    oerationSelf: {
                        label: "操作",
                        width: 158,
                        fixed: "right",
                    },
                },
                tableData: []
            }
        },
        mounted() {
            this.$nextTick(() => {
                this.getList()
            })
        },
        methods: {
            sort_change(column) {
                Object.assign(this.queryForm, {
                    order: column.order == "ascending" ? 'ASC' : 'DESC',
                    orderby: column.prop,
                });
                this.getList();
            }, // 排序
            getList() {
                axios.post('/wp-json/get/infos/list', {}).then(Response => {
                    this.tableData = Response.data
                }).catch(e => {
                    console.log(e);
                });
            },
            clone(obj) {
                var o;
                // 如果  他是对象object的话  , 因为null,object,array  也是'object';
                if (typeof obj === 'object') {

                    // 如果  他是空的话
                    if (obj === null) {
                        o = null;
                    } else {

                        // 如果  他是数组arr的话
                        if (obj instanceof Array) {
                            o = [];
                            for (var i = 0, len = obj.length; i < len; i++) {
                                o.push(this.clone(obj[i]));
                            }
                        }
                        // 如果  他是对象object的话
                        else {
                            o = {};
                            for (var j in obj) {
                                o[j] = this.clone(obj[j]);
                            }
                        }

                    }
                } else {
                    o = obj;
                }
                return o;
            }
        }
    });
    app.use(ElementPlus).mount('#app')
</script>