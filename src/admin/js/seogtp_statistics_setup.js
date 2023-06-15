const app = Vue.createApp({
    data() {
        return {
            columns: {
                ip: {
                    label: "访问ip",
                },
                device: {
                    label: "设备",
                },
                browser: {
                    label: "浏览器",
                },
                country: {
                    label: "国家",
                },
                isNew: {
                    label: "是否新访客",
                },
                accessSource: {
                    label: "访问来源"
                },
                accessSourceType: {
                    label: "访问来源类型"
                },
                accessSourcePage: {
                    label: "访问来源页面"
                },
                visitPage: {
                    label: "访问页面"
                },
                enterTime: {
                    label: "开始时间"
                },
                leaveTime: {
                    label: "离开时间"
                }
            },
            tableData: [
                { ip: '1', device: '电脑', browser: '谷歌', country: '美国', isNew: '是', accessSource: "facebook", accessSourceType: "社交媒体", accessSourcePage: 'www.facebook.com/asda%da412563', visitPage: "/", enterTime: "2023/6/13 9:12:20", leaveTime: "2023/6/13 9:12:59", },
                { ip: '2', device: '电脑', browser: '谷歌', country: '日本', isNew: '否', accessSource: "google", accessSourceType: "搜索引擎", accessSourcePage: 'www.google.com/?search=asda%da412563', visitPage: "/", enterTime: "2023/6/13 9:12:20", leaveTime: "2023/6/13 9:12:59", },
                { ip: '2', device: '手机', browser: '火狐', country: '日本', isNew: '是', accessSource: "", accessSourceType: "直接进入", accessSourcePage: '', visitPage: "/contact", enterTime: "2023/6/13 8:12:20", leaveTime: "2023/6/13 8:12:59" }
            ],
        }
    },
    mounted() {
    },
    methods: {
    }
})
app.use(ElementPlus);
app.mount("#seogtp_statistics_setup");