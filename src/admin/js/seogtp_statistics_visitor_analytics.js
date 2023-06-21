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

import { markRaw } from 'vue'
const app = Vue.createApp({
	data() {
		return {
			activeName: 'first',
			queryForm: {
				order: 'ASC',
				orderby: "menu_order",
				data: {},
			},
			columns: {
				ip: {
					label: "ip",
				},
				email: {
					label: "注册邮箱",
				},
				country: {
					label: "国家",
				},
				city: {
					label: "城市",
				},
				province: {
					label: "州/省",
				},
				device: {
					label: "设备",
				},
				browser: {
					label: "浏览器",
				},
				visitTime: {
					label: "访问时间",
				},
				leaveTime: {
					label: "离开时间",
				},
				status: {
					label: "状态",
				},
				visitCount: {
					label: "访问次数",
				},
			},
			tableData: [],

			/**假数据 */
			randomCountries: [
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
		this.getData()
	},
	methods: {
		getData() {
			let getRandomItem = (array) => {
				return array[Math.floor(Math.random() * array.length)];
			}// 定义一个函数，用于从数组中选择一个随机元素
			var data = [];

			for (var i = 0, visitTime = new Date().getTime() - 120 * 1000; i < 20; i++) {
				let randomCountry = getRandomItem(this.randomCountries);
				let randomCity = getRandomItem(randomCountry.cities);

				visitTime = visitTime - Math.random() * 1000;
				let leaveTime = visitTime + Math.random() * 120 * 1000;

				let visitCount = Math.floor(Math.random() * 5) + 1
				let status = getRandomItem(this.randomOnlineStatuses)
				let email = getRandomItem(this.randomOnlineStatuses) ? `example@${i}.com` : ''

				data.push({
					id: i,
					ip: `000000${19 - i}`,
					email: email,
					country: randomCountry.country,
					city: randomCity.city,
					province: randomCity.provinces,
					device: getRandomItem(this.randomDevices),
					browser: getRandomItem(this.randomBrowsers),
					visitTime: this.formatDate(visitTime),
					leaveTime: this.formatDate(leaveTime),
					status: status == 1 ? '在线' : '离线',
					visitCount: visitCount  // 随机生成1-10的访问次数
				});
			}
			this.tableData = data;
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
app.mount("#seogtp_statistics_visitor_analytics");
