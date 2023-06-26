import * as echarts from 'echarts';
// 引入柱状图图表，图表后缀都为 Chart
import { BarChart } from 'echarts/charts';
// 引入提示框，标题，直角坐标系，数据集，内置数据转换器组件，组件后缀都为 Component
import {
    TitleComponent,
    TooltipComponent,
    GridComponent,
    DatasetComponent,
    TransformComponent
} from 'echarts/components';
// 标签自动布局、全局过渡动画等特性
import { LabelLayout, UniversalTransition } from 'echarts/features';
// 引入 Canvas 渲染器，注意引入 CanvasRenderer 或者 SVGRenderer 是必须的一步
import { CanvasRenderer } from 'echarts/renderers';
echarts.use([
    TitleComponent,
    TooltipComponent,
    GridComponent,
    DatasetComponent,
    TransformComponent,
    BarChart,
    LabelLayout,
    UniversalTransition,
    CanvasRenderer
]);
// 注册必须的组件
import { mdiCalendarMonth } from '@mdi/js';

import { markRaw } from 'vue'
const app = Vue.createApp({
    data() {
        return {
            choosedTime: [], // 当前选择时间
            compareTime: [], // 对比时间
            xData: [], // 时间轴
            choosedTimeStr: '',// 当前选择时间通译
            compareTimeStr: '',// 对比时间通译
            differenceInDays: 0,
            shortcuts: [
                {
                    text: '今天',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        return [start, end]
                    },
                },

                {
                    text: '昨天',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 1)
                        end.setTime(end.getTime() - 3600 * 1000 * 24 * 1)
                        return [start, end]
                    },
                },
                {
                    text: '过去7天',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 6)
                        return [start, end]
                    },
                },
                {
                    text: '过去30天',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 29)
                        return [start, end]
                    },
                },
                {
                    text: '过去90天',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 89)
                        return [start, end]
                    },
                }
            ],
            mdiCalendarMonth: mdiCalendarMonth,
            activeName: 'first',
            queryForm: {
                order: 'ASC',
                orderby: "menu_order",
                data: {},
            },
            columns: {
                visitPage: {
                    label: "受访页面",
                },
                uv: {
                    label: "独立IP访客量",
                },
                visitCount: {
                    label: "访问次数",
                },
                pvUv: {
                    label: "访问深度",
                },
                averageVisitTime: {
                    label: "平均访问时长",
                },
                visitBounceRate: {
                    label: "跳出率",
                },
                visitExitRate: {
                    label: "退出率",
                },
                operation: {
                    label: '操作'
                }
            },
            tableData: [],
            dialogFormVisible: false,
            singleData: {},
            singleDataVisitorType: 'all',
            radio3: '流量来源',
            singleDataCountry: '',
            singleDataTableData: [],
            singleDataColumns: {
                refferPage: {
                    label: "流量来源",
                },
                uv: {
                    label: "独立IP访客量",
                },
                visitCount: {
                    label: "访问次数",
                },
                pvUv: {
                    label: "访问深度",
                },
                averageVisitTime: {
                    label: "平均访问时长",
                },
                visitBounceRate: {
                    label: "跳出率",
                },
                visitExitRate: {
                    label: "退出率",
                }
            },
            /**假数据 */
            randomSourcePage: [
                'https://www.facebook.com/5g6h7j8k9',
                'https://www.baidu.com/6f7g8h9i0j',
                'https://www.google.com/e5f6g7h8',
                'https://www.youtube.com/ad/9i0j1k2l',
            ],
            randomVisitPage: [
                '/about-weller',
                '/',
                '/contact',
                '/pcb_assembly/quote',
                '/wellerpcb_news',
                '/pcb-ipc-specific-minimum-surface-conductor-thickness',
                '/pcb_layout_design/pcb_layout_design_quotation',
                '/our_services/electronics-manufacturing-services',
                '/pcb_fabrication/halogen-free',
                '/faq',
                '/pcb_fabrication/top-pcb-manufacturers-via-in-pad',
                '/pcb_fabrication/top-flexible-printed-circuits-manufacturer',
                '/pcb_fabrication/printed-circuit-boards-manufacturing-ipc-class-2-and-3',
                '/pcb_fabrication/printed-circuit-board-dfm-analysis',
                '/pcb_fabrication/advanced_hdi_pcb_manufacturing_capability',
                '/pcb_fabrication/altium-designer-to-gerber-file',
                '/price',
            ],
            randomCountries: [
                {
                    country: "全部",
                },
                {
                    country: "美国",
                    cities: [
                        { city: "纽约", provinces: "纽约州" },
                        { city: "洛杉矶", provinces: "加利福尼亚州" },
                        { city: "芝加哥", provinces: "伊利诺伊州" },
                        { city: "休斯顿", provinces: "德克萨斯州" },
                        { city: "费城", provinces: "宾夕法尼亚州" },
                        { city: "菲尼克斯", provinces: "亚利桑那州" },
                        { city: "圣安东尼奥", provinces: "德克萨斯州" },
                        { city: "圣地亚哥", provinces: "加利福尼亚州" },
                        { city: "达拉斯", provinces: "德克萨斯州" },
                        { city: "圣何塞", provinces: "加利福尼亚州" },
                    ],
                },
                {
                    country: "加拿大",
                    cities: [
                        { city: "多伦多", provinces: "安大略省" },
                        { city: "温哥华", provinces: "不列颠哥伦比亚省" },
                        { city: "蒙特利尔", provinces: "魁北克省" },
                        { city: "卡尔加里", provinces: "艾伯塔省" },
                        { city: "渥太华", provinces: "安大略省" },
                        { city: "埃德蒙顿", provinces: "艾伯塔省" },
                        { city: "魁北克市", provinces: "魁北克省" },
                        { city: "温尼伯", provinces: "马尼托巴省" },
                        { city: "汉密尔顿", provinces: "安大略省" },
                        { city: "萨斯卡通", provinces: "萨斯喀彻温省" },
                    ],
                },
                {
                    country: "澳大利亚",
                    cities: [
                        { city: "悉尼", provinces: "新南威尔士州" },
                        { city: "墨尔本", provinces: "维多利亚州" },
                        { city: "布里斯班", provinces: "昆士兰州" },
                        { city: "珀斯", provinces: "西澳大利亚州" },
                        { city: "阿德莱德", provinces: "南澳大利亚州" },
                        { city: "黄金海岸", provinces: "昆士兰州" },
                        { city: "堪培拉", provinces: "澳大利亚首都地区" },
                        { city: "纽卡斯尔", provinces: "新南威尔士州" },
                        { city: "中央海岸", provinces: "新南威尔士州" },
                        { city: "卧龙岗", provinces: "新南威尔士州" },
                    ],
                },
                {
                    country: "意大利",
                    cities: [
                        { city: "罗马", provinces: "拉齐奥大区" },
                        { city: "米兰", provinces: "伦巴第大区" },
                        { city: "那不勒斯", provinces: "坎帕尼亚大区" },
                        { city: "都灵", provinces: "皮埃蒙特大区" },
                        { city: "帕尔马", provinces: "艾米利亚-罗马涅大区" },
                        { city: "热那亚", provinces: "利古里亚大区" },
                        { city: "博洛尼亚", provinces: "艾米利亚-罗马涅大区" },
                        { city: "弗洛伦萨", provinces: "托斯卡纳大区" },
                        { city: "威尼斯", provinces: "威尼托大区" },
                        { city: "维罗纳", provinces: "威尼托大区" },
                    ],
                },
                {
                    country: "印度",
                    cities: [
                        { city: "孟买", provinces: "马哈拉施特拉邦" },
                        { city: "新德里", provinces: "德里" },
                        { city: "孟加拉罗尔", provinces: "卡纳塔克邦" },
                        { city: "海得拉巴", provinces: "特伦甘纳邦" },
                        { city: "艾哈迈达巴德", provinces: "古吉拉特邦" },
                        { city: "清奈", provinces: "泰米尔纳" }
                    ]
                },
                {
                    country: "德国",
                    cities: [
                        { city: "柏林", provinces: "柏林" },
                        { city: "汉堡", provinces: "汉堡" },
                        { city: "慕尼黑", provinces: "巴伐利亚" },
                        { city: "科隆", provinces: "北莱茵-威斯特法伦" },
                        { city: "法兰克福", provinces: "黑森" }
                    ],
                }
            ],
            randomDevices: ['手机', '电脑', '平板'],
            randomBrowsers: ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'],
            randomOnlineStatuses: [1, 0]
        }
    },
    mounted() {
        /**时间调整 */
        const today = new Date()
        let yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        this.choosedTime = [today, today]
        this.choosedTimeStr = '今天'
        this.compareTime = [yesterday, yesterday]
        this.compareTimeStr = '昨天'
        this.xData = (() => {
            var data = [];
            for (var i = 0; i < 24; i++) {
                data.push(i + "时");
            }
            return data;
        })()
        /**时间调整 */
        this.getData()
    },
    methods: {
        getData() {
            this.tableData = this.randomVisitPage.map((item, i) => {
                let uv = (20 - i) * 3
                let visitCount = uv * (Math.floor(Math.random() * 21) + 1)
                let averageVisitTime = Math.floor(Math.random() * 121) + 4
                let visitBounceRate = (Math.random() * 31 + 0.01).toFixed(2)
                let visitExitRate = (Math.random() * 81 + 0.01).toFixed(2)
                return {
                    id: i,
                    visitPage: item,
                    uv: uv,
                    visitCount: visitCount,
                    pvUv: visitCount / uv,
                    averageVisitTime: averageVisitTime + '秒',
                    visitBounceRate: visitBounceRate + '%',
                    visitExitRate: visitExitRate + '%',
                }
            })
        },
        handlelookPop(row) {
            this.singleData = row
            this.dialogFormVisible = true
            this.singleDataTableData = this.randomSourcePage.map((item, i) => {
                let uv = (10 - i)
                let visitCount = uv * (Math.floor(Math.random() * 21) + 1)
                let averageVisitTime = Math.floor(Math.random() * 121) + 4
                let visitBounceRate = (Math.random() * 31 + 0.01).toFixed(2)
                let visitExitRate = (Math.random() * 81 + 0.01).toFixed(2)
                return {
                    id: i,
                    refferPage: item,
                    uv: uv,
                    visitCount: visitCount,
                    pvUv: visitCount / uv,
                    averageVisitTime: averageVisitTime + '秒',
                    visitBounceRate: visitBounceRate + '%',
                    visitExitRate: visitExitRate + '%',
                }
            })
        },
        handleDialogClose() {
            this.dialogFormVisible = false
        },
        singleDataQuery(type, val) {
            if (type == 'country') {
                if (val[val.length - 1] == '全部') {
                    this.singleDataCountry = ['全部']
                } else if (val[val.length - 1] != '全部' && val.includes('全部')) {
                    this.singleDataCountry.splice(0, 1);
                }
            }
        },
        formatDate(date) {
            let now = new Date(date);

            let year = now.getFullYear(); // 获取年份
            let month = ("0" + (now.getMonth() + 1)).slice(-2); // 获取月份，注意 JavaScript 中月份是从 0 开始计数的，所以需要 +1
            let day = ("0" + now.getDate()).slice(-2); // 获取日

            let hours = ("0" + now.getHours()).slice(-2); // 获取小时
            let minutes = ("0" + now.getMinutes()).slice(-2); // 获取分钟
            let seconds = ("0" + now.getSeconds()).slice(-2); // 获取秒

            let formattedDate = `${year}.${month}.${day} ${hours}:${minutes}:${seconds}`;

            return formattedDate;
        }

    }
})
app.use(ElementPlus);
app.mount("#seogtp_statistics_pages_view_analytics");
