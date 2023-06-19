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
import { mdiCalendarMonth } from '@mdi/js';
// 注册必须的组件

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
			echart1: {
				echart: null,
				dataNews: [],
				dataOlds: [],
			},// 访客总计
			echart2: {
				echart: null,
				dataNews: [],
				dataOlds: [],
			},// 当前选择时间内新旧访客占比
			echart3: {
				echart: null,
				data: []
			},
			echart4: {
				echart: null,
				data: []
			},
			echart5: {
				echart: null,
				data: []
			},
			echart6: {
				echart: null,
				data: []
			},

			option7: [
				{
					page: 'com.google.android.gm',
					value: 870,
				},
				{
					page: 'www.dianxiaomi.com',
					value: 750,
				},
				{
					page: 'https://pictogrammers.com/library/mdi/',
					value: 471,
				},
				{
					page: 'https://tongji.baidu.com/main/overview/demo/overview/index?siteId=16847648',
					value: 170,
				},
				{
					page: "https://www.deepl.com/translator#en/zh/We've%20delivered%20your%20parcel%20to%20a%20secure%20location%20at%20the%20delivery%20address",
					value: 5,
				}
			],
			option8: [
				{
					page: '/',
					value: 1270,
					ringRate: -20
				},
				{
					page: '/about-weller',
					value: 970,
					ringRate: -31
				},
				{
					page: '/contact',
					value: 370,
					ringRate: 20

				},
				{
					page: '/wellerpcb_news',
					value: 72,
					ringRate: 120
				}
			],
			option9: [
				{
					page: '/',
					value: '00:15:20'
				},
				{
					page: '/about-weller',
					value: 80,
					value: '00:10:20'

				},
				{
					page: '/contact',
					value: 500,
					value: '00:04:20'
				},
				{
					page: '/wellerpcb_news',
					value: 100,
					value: '00:00:58'

				}
			]
		}
	},
	mounted() {
		this.initialChatsData()
	},
	methods: {
		initialChatsData() {
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
			this.xData.forEach((item, i) => {
				let x1 = Math.ceil(Math.random() * 100) + 2 * i;
				let x2 = Math.ceil(Math.random() * 100);
				this.echart1.dataNews.push(x1 + (i == 0 ? 0 : this.echart1.dataNews[i - 1]));
				this.echart1.dataOlds.push(x2 + (i == 0 ? 0 : this.echart1.dataOlds[i - 1]));
				this.echart2.dataNews.push(x1 - 2 * i);
				this.echart2.dataOlds.push(2 * i);
			})

			this.echart1.echart = markRaw(echarts.init(document.getElementById('line1')));
			this.echart1.echart.setOption(this.lineEchart(this.echart1.dataNews, this.echart1.dataOlds));

			this.echart2.echart = markRaw(echarts.init(document.getElementById('line2')));
			this.echart2.echart.setOption(this.barEchart1(this.echart2.dataNews, this.echart2.dataOlds));
			/**************************访客来源 */
			let getRandomInt = (min, max) => {
				min = Math.ceil(min);
				max = Math.floor(max);
				return Math.floor(Math.random() * (max - min + 1)) + min;
			}
			let getRandomTwoDigit = () => {
				// 生成10到99之间的随机整数
				let num = getRandomInt(10, 99);
				// 生成一个0到1之间的随机数，如果它小于0.5，我们就将num变为负数
				if (Math.random() < 0.5) {
					num = -num;
				}
				return num;
			}

			let viewSource = ['社交媒体', '搜索引擎', '直接访问', '邮件营销', '未知'];
			let socialMedia = ['Facebook', 'Twitter', 'Instagram', 'LinkedIn', 'Snapchat', 'YouTube', 'Pinterest', 'Reddit', 'WhatsApp', 'WeChat'];
			let device = ['电脑', '手机', '平板'];
			let countries = ['美国', '法国', '德国', '意大利', '英国', '印度', '中国', '日本', '加拿大', '澳大利亚'];
			// 随机打乱数组
			viewSource.sort(() => Math.random() - 0.5);
			countries.sort(() => Math.random() - 0.5);
			device.sort(() => Math.random() - 0.5);
			socialMedia.sort(() => Math.random() - 0.5);

			for (let i = 0; i < 5; i++) {
				this.echart3.data.push({
					name: viewSource[i],
					value: Math.floor(Math.random() * 1000),  // 生成一个0-999的随机数
					ringRate: getRandomTwoDigit()
				});
			}
			for (let i = 0; i < 3; i++) {
				this.echart5.data.push({
					name: device[i],
					value: Math.floor(Math.random() * 1000),  // 生成一个0-999的随机数
					ringRate: getRandomTwoDigit()
				});
			}
			for (let i = 0; i < 6; i++) {
				this.echart4.data.push({
					name: countries[i],
					value: Math.floor(Math.random() * 1000),  // 生成一个0-999的随机数
					ringRate: getRandomTwoDigit()
				});
				this.echart6.data.push({
					name: socialMedia[i],
					value: Math.floor(Math.random() * 1000),  // 生成一个0-999的随机数
					ringRate: getRandomTwoDigit()
				});
			}
			this.echart3.data.sort((a, b) => b.value - a.value);
			this.echart4.data.sort((a, b) => b.value - a.value);
			this.echart5.data.sort((a, b) => b.value - a.value);
			this.echart6.data.sort((a, b) => b.value - a.value);


			this.echart3.echart = markRaw(echarts.init(document.getElementById('line3')));
			this.echart4.echart = markRaw(echarts.init(document.getElementById('line4')));
			this.echart5.echart = markRaw(echarts.init(document.getElementById('line5')));
			this.echart6.echart = markRaw(echarts.init(document.getElementById('line6')));
			this.echart3.echart.setOption(this.barEchart(this.echart3.data));
			this.echart4.echart.setOption(this.barEchart(this.echart4.data));
			this.echart5.echart.setOption(this.barEchart(this.echart5.data));
			this.echart6.echart.setOption(this.barEchart(this.echart6.data));
			/**************************访客来源 */

		}, // 初始化所有图表数据
		updateChatsData() {
			this.differenceInDays = (new Date(this.choosedTime[1]).getTime() - new Date(this.choosedTime[0]).getTime()) / (1000 * 3600 * 24)
			this.echart1.dataNews = [];
			this.echart1.dataOlds = [];
			this.echart2.dataNews = [];
			this.echart2.dataOlds = [];
			this.echart3.data = [];
			this.echart4.data = [];
			this.echart5.data = [];
			this.echart6.data = [];
			this.xData.forEach((item, i) => {
				let x1 = Math.ceil(Math.random() * 100) + 2 * i;
				let x2 = Math.ceil(Math.random() * 100);
				if (this.differenceInDays) {
					this.echart1.dataNews.push(x1);
					this.compareTimeStr ? this.echart1.dataOlds.push(x2) : '';
				} else {
					this.echart1.dataNews.push(x1 + (i == 0 ? 0 : this.echart1.dataNews[i - 1]));
					this.compareTimeStr ? this.echart1.dataOlds.push(x2 + (i == 0 ? 0 : this.echart1.dataOlds[i - 1])) : '';
				}
				this.echart2.dataNews.push(x1 - 2 * i);
				this.echart2.dataOlds.push(2 * i);
			})

			this.echart1.echart.clear()
			this.echart1.echart.setOption(this.lineEchart(this.echart1.dataNews, this.echart1.dataOlds));

			this.echart2.echart.clear()
			this.echart2.echart.setOption(this.barEchart1(this.echart2.dataNews, this.echart2.dataOlds));
			/**************************访客来源 */
			let getRandomInt = (min, max) => {
				min = Math.ceil(min);
				max = Math.floor(max);
				return Math.floor(Math.random() * (max - min + 1)) + min;
			}
			let getRandomTwoDigit = () => {
				// 生成10到99之间的随机整数
				let num = getRandomInt(10, 99);
				// 生成一个0到1之间的随机数，如果它小于0.5，我们就将num变为负数
				if (Math.random() < 0.5) {
					num = -num;
				}
				return num;
			}

			let viewSource = ['社交媒体', '搜索引擎', '直接访问', '邮件营销', '未知'];
			let socialMedia = ['Facebook', 'Twitter', 'Instagram', 'LinkedIn', 'Snapchat', 'YouTube', 'Pinterest', 'Reddit', 'WhatsApp', 'WeChat'];
			let device = ['电脑', '手机', '平板'];
			let countries = ['美国', '法国', '德国', '意大利', '英国', '印度', '中国', '日本', '加拿大', '澳大利亚'];
			// 随机打乱数组
			viewSource.sort(() => Math.random() - 0.5);
			countries.sort(() => Math.random() - 0.5);
			device.sort(() => Math.random() - 0.5);
			socialMedia.sort(() => Math.random() - 0.5);

			for (let i = 0; i < 5; i++) {
				this.echart3.data.push({
					name: viewSource[i],
					value: Math.floor(Math.random() * 1000),  // 生成一个0-999的随机数
				});
				this.compareTimeStr ? this.echart3.data[i].ringRate = getRandomTwoDigit() : ''
			}
			for (let i = 0; i < 3; i++) {
				this.echart5.data.push({
					name: device[i],
					value: Math.floor(Math.random() * 1000),  // 生成一个0-999的随机数
				});
				this.compareTimeStr ? this.echart5.data[i].ringRate = getRandomTwoDigit() : ''
			}
			for (let i = 0; i < 6; i++) {
				this.echart4.data.push({
					name: countries[i],
					value: Math.floor(Math.random() * 1000),  // 生成一个0-999的随机数
				});
				this.compareTimeStr ? this.echart4.data[i].ringRate = getRandomTwoDigit() : ''
				this.echart6.data.push({
					name: socialMedia[i],
					value: Math.floor(Math.random() * 1000),  // 生成一个0-999的随机数
				});
				this.compareTimeStr ? this.echart6.data[i].ringRate = getRandomTwoDigit() : ''
			}
			this.echart3.data.sort((a, b) => b.value - a.value);
			this.echart4.data.sort((a, b) => b.value - a.value);
			this.echart5.data.sort((a, b) => b.value - a.value);
			this.echart6.data.sort((a, b) => b.value - a.value);


			this.echart3.echart.clear()
			this.echart4.echart.clear()
			this.echart5.echart.clear()
			this.echart6.echart.clear()
			this.echart3.echart.setOption(this.barEchart(this.echart3.data));
			this.echart4.echart.setOption(this.barEchart(this.echart4.data));
			this.echart5.echart.setOption(this.barEchart(this.echart5.data));
			this.echart6.echart.setOption(this.barEchart(this.echart6.data));

		},// 更新所有图表数据
		openDatePicker(DatePicker) {
			if (DatePicker == 'choosedTime' && this.shortcuts[0].text == '无对比') {
				this.shortcuts.splice(0, 1);
			} else if (DatePicker == 'compareTime' && this.shortcuts[0].text != '无对比') {
				this.shortcuts.unshift({
					text: '无对比',
					value: () => {
						return ['', '']
					},
				},);
			}
			this.$refs[DatePicker].focus()
		},//按钮打开日期选择器
		echart1Fun() {
			let choosedDataTotal = this.differenceInDays ? eval(this.echart1.dataNews.join('+')) : this.echart1.dataNews[this.echart1.dataNews.length - 1],
				compareDataTotal = null,
				ringRate = null;
			if (this.echart1.dataOlds.length) {
				compareDataTotal = this.differenceInDays ? eval(this.echart1.dataOlds.join('+')) : this.echart1.dataOlds[this.echart1.dataOlds.length - 1];
				ringRate = ((choosedDataTotal - compareDataTotal) / compareDataTotal * 100).toFixed(2)
			}
			return {
				choosedDataTotal, compareDataTotal, ringRate
			}
		}, //数据处理
		echart2Fun() {
			let choosedDataNewsTotal = eval(this.echart2.dataNews.join('+')),
				choosedDataOldsTotal = eval(this.echart2.dataOlds.join('+')),
				choosedDataTotal = choosedDataNewsTotal + choosedDataOldsTotal,
				choosedDataNewsTotalPercent = (choosedDataNewsTotal / choosedDataTotal * 100).toFixed(2),
				choosedDataOldsTotalPercent = (choosedDataOldsTotal / choosedDataTotal * 100).toFixed(2),
				choosedDataNewsTotalRingRate = null,
				choosedDataOldsTotalRingRate = null;
			if (this.echart1.dataOlds.length) {
				choosedDataNewsTotalRingRate = ((choosedDataNewsTotal - choosedDataOldsTotal) / choosedDataTotal * 100).toFixed(2)
				choosedDataOldsTotalRingRate = ((choosedDataOldsTotal - choosedDataNewsTotal) / choosedDataTotal * 100).toFixed(2)
			}
			return {
				choosedDataNewsTotal,
				choosedDataOldsTotal,
				choosedDataNewsTotalPercent,
				choosedDataOldsTotalPercent,
				choosedDataNewsTotalRingRate,
				choosedDataOldsTotalRingRate,
			}
		}, //数据处理
		/*************************定义图表类型 */
		barEchart(dataBase) {
			var top10name = dataBase.map((item) => item.name);
			var top10value = dataBase.map((item) => item.value);
			var color = ["#0d6efd", "#0d6efd", "#0d6efd"];
			var color1 = ["#6610f2", "#6610f2", "#6610f2"];

			let lineY = [];
			let lineT = [];
			for (var i = 0; i < dataBase.length; i++) {
				var x = i;
				if (x > 1) {
					x = 2;
				}
				var data = {
					name: top10name[i],
					color: color[x],
					value: top10value[i],
					barGap: "-100%",
					itemStyle: {
						normal: {
							show: true,
							borderWidth: 2,
							color: new echarts.graphic.LinearGradient(
								0,
								0,
								1,
								0,
								[
									{
										offset: 0,
										color: color[x],
									},
									{
										offset: 1,
										color: color1[x],
									},
								],
								false
							),
							barBorderRadius: 10,
						},
					},
				};
				var data1 = {
					value: top10value[i],
					barGap: "-100%",
					itemStyle: {
						color: "#00123500", //剩余部分颜色
						barBorderRadius: 10,
					},
					label: {
						show: true,
						formatter: top10value[i],
						position: "right",
					},
				};
				lineY.push(data);
				lineT.push(data1);
			}

			let option = {
				tooltip: {
					trigger: "item",
					formatter: (p) => {
						if (p.seriesName === "total") {
							return "";
						}
						return `${p.name}<br/>${p.value}`;
					},
				},
				grid: {
					top: 0,
					left: 0,
					right: "10%",
					bottom: 0,
				},
				dataZoom: [
					{
						type: "inside",
						yAxisIndex: 0,
						end: dataBase.length > 5 ? ((5 - 0.01) / dataBase.length) * 100 : 100,
						zoomOnMouseWheel: false,
						moveOnMouseMove: true,
						moveOnMouseWheel: true,
					},
				],
				color: color,
				yAxis: [
					{
						type: "category",
						inverse: true,
						axisTick: {
							show: false,
						},
						axisLine: {
							show: false,
						},
						data: top10name,
					},
				],
				xAxis: {
					type: "value",
					axisTick: {
						show: false,
					},
					axisLine: {
						show: false,
					},
					splitLine: {
						show: false,
					},
					axisLabel: {
						show: false,
					},
				},
				series: [
					{
						name: "total",
						type: "bar",
						barGap: "-100%",
						barWidth: "15px",
						data: lineT,
						legendHoverLink: false,
					},
					{
						name: "bar",
						type: "bar",
						barWidth: "15px",
						data: lineY,
						label: {
							normal: {
								color: "#b3ccf8",
								show: true,
								position: [0, "-20px"],
								formatter: function (a) {
									let num = a.dataIndex + 1;
									let ringRate = dataBase[a.dataIndex].ringRate;
									let str = `{color1|${num}} {color2|${a.name}}`;
									if (ringRate) {
										str += ` {${ringRate >= 0 ? 'color4' : 'color3'}|${ringRate >= 0 ? '↑' : '↓'} ${Math.abs(ringRate)}%}`
									}
									return str;
								},
								rich: {
									color1: {
										color: "#000",
										fontSize: "14",
										fontWeight: 500,
									},
									color2: {
										color: "#000",
										fontSize: "14",
										fontWeight: 700,
									},
									color3: {
										color: "#dc3545",
										fontSize: "12",
										fontWeight: 700,
									},
									color4: {
										color: "#198754",
										fontSize: "12",
										fontWeight: 700,
									}
								},
							},
						},
					},
				],
			};
			return option;
		},
		barEchart1(dataNews, dataOlds) {
			let option = {
				tooltip: {
					trigger: "axis",
					axisPointer: {
						type: "shadow",
						textStyle: {
							color: "#fff",
						},
					},
				},
				grid: {
					top: 30,
					right: 0,
					bottom: 20,
				},
				legend: {
					right: 0,
					textStyle: {
						color: "#90979c",
					},
					data: ["老访客", "新访客"],
				},
				calculable: true,
				xAxis: [
					{
						type: "category",
						axisLine: {
							lineStyle: {
								color: "#90979c",
							},
						},
						splitLine: {
							show: false,
						},
						axisTick: {
							show: false,
						},
						splitArea: {
							show: false,
						},
						axisLabel: {
							interval: 'auto',
						},
						minorTick: {
							show: true,
						},
						data: this.xData,
					},
				],
				yAxis: [
					{
						type: "value",
						splitLine: {
							show: false,
						},
						axisLine: {
							lineStyle: {
								color: "#90979c",
							},
						},
						axisTick: {
							show: false,
						},
						axisLabel: {
							interval: 0,
						},
						splitArea: {
							show: false,
						},
					},
				],
				series: [
					{
						name: "老访客",
						type: "bar",
						stack: "总量",
						itemStyle: {
							normal: {
								color: "rgba(255,144,128,1)",
								label: {
									show: true,
									textStyle: {
										color: "#fff",
									},
									position: "inside",
									formatter: function (p) {
										return p.value > 0 ? p.value : "";
									},
								},
							},
						},
						data: dataOlds,
					},

					{
						name: "新访客",
						type: "bar",
						stack: "总量",
						itemStyle: {
							normal: {
								color: "rgba(0,191,183,1)",
								barBorderRadius: 0,
								label: {
									show: true,
									position: "inside",
									formatter: function (p) {
										return p.value > 0 ? p.value : "";
									},
								},
							},
						},
						data: dataNews,
					},
				],
			};
			return option
		},
		lineEchart(dataNews, dataOlds) {
			let legendData = [this.choosedTimeStr];
			let seriesData = [{
				name: this.choosedTimeStr,
				type: "line",
				smooth: true,
				showSymbol: false,
				symbol: "circle",
				symbolSize: 6,
				data: dataNews,
				areaStyle: {
					normal: {
						color: new echarts.graphic.LinearGradient(
							0,
							0,
							0,
							1,
							[
								{
									offset: 0,
									color: "rgba(199, 237, 250,0.5)",
								},
								{
									offset: 1,
									color: "rgba(199, 237, 250,0.2)",
								},
							],
							false
						),
					},
				},
				itemStyle: {
					normal: {
						color: "#f7b851",
					},
				},
				lineStyle: {
					normal: {
						width: 2,
					},
				},
			}];
			if (dataOlds.length) {
				legendData.push(this.compareTimeStr)
				seriesData.push({
					name: this.compareTimeStr,
					type: "line",
					smooth: true,
					showSymbol: false,
					symbol: "circle",
					symbolSize: 6,
					data: dataOlds,
					areaStyle: {
						normal: {
							color: new echarts.graphic.LinearGradient(
								0,
								0,
								0,
								1,
								[
									{
										offset: 0,
										color: "rgba(216, 244, 247,1)",
									},
									{
										offset: 1,
										color: "rgba(216, 244, 247,1)",
									},
								],
								false
							),
						},
					},
					itemStyle: {
						normal: {
							color: "#58c8da",
						},
					},
					lineStyle: {
						normal: {
							width: 2,
						},
					},
				})
			}

			let option = {
				grid: {
					top: 30,
					right: 0,
					bottom: 20,
				},
				tooltip: {
					trigger: "axis",
					formatter: (params) => {
						console.log(params);
						return params
							.map((item) => {
								let label = `<div>${item.name}</div>`;
								if (!label.includes("时")) {
									if (item.componentIndex == 1) {
										let initDay = item.seriesName.split(" - ")[0];
										label = `<div>${this.formatDate(
											new Date(initDay).getTime() + item.dataIndex * 24 * 3600 * 1000
										)}</div>`;
									}
								} else {
									label = `<div>${item.seriesName}(${item.name})</div>`
								}
								return `<div style="color:${item.color}">
											<div style="display:inline-block;vertical-align:middle;text-align:center">
												${label}
											</div>
											<div style="display:inline-block;vertical-align:middle;color:#000">
												: ${item.value}
											</div>
						  				</div>`;
							})
							.join("");
					},
					axisPointer: {
						lineStyle: {
							color: "#ddd",
						},
					}
				},
				legend: {
					right: 0,
					data: legendData,
				},
				xAxis: {
					type: "category",
					data: this.xData,
					boundaryGap: false,
					splitLine: {
						show: true,
						interval: "auto",
						lineStyle: {
							color: ["#D4DFF5"],
						},
					},
				},
				yAxis: {
					type: "value",
					splitLine: {
						lineStyle: {
							color: ["#D4DFF5"],
						},
					},
				},
				series: seriesData,
			};
			return option
		},
		/*************************定义图表类型 */
		disabledDate(time) {
			return time.getTime() > Date.now();
		}, // 禁止选择今天之后的时间
		compareDate(val, DateFilter) {
			/**
			 * 对比时间以当前选择时间为主。
			 * 当前选择时间是一天的话， 对比时间以结束时间为主。时间轴为时段
			 * 当前选择时间是多天的话， 对比时间以结束时间为主的同样的天数。时间轴为日期。
			 * 最后时间相同的话， 则无对比时间。
			 */
			this.differenceInDays = (new Date(this.choosedTime[1]).getTime() - new Date(this.choosedTime[0]).getTime()) / (1000 * 3600 * 24)

			if (DateFilter == 'compareTime' && val[0] != 'Invalid Date') {
				this.compareTime = [new Date(new Date(val[1]).getTime() - this.differenceInDays * 24 * 3600 * 1000), val[1]]
			}
			if (DateFilter == 'choosedTime') {
				this.compareTime = []
			}

			if (this.differenceInDays) {
				this.xData = (() => {
					var data = [];
					for (var i = 0; i <= this.differenceInDays; i++) {
						data.push(this.formatDate(new Date(this.choosedTime[0]).getTime() + i * 24 * 3600 * 1000));
					}
					return data;
				})()
			} else {
				this.xData = (() => {
					var data = [];
					for (var i = 0; i < 24; i++) {
						data.push(i + "时");
					}
					return data;
				})()
			}

			let choosedTimeStr = `${this.formatDate(new Date(this.choosedTime[0]))} - ${this.formatDate(new Date(this.choosedTime[1]))}`
			let compareTimeStr = `${this.formatDate(new Date(this.compareTime[0]))} - ${this.formatDate(new Date(this.compareTime[1]))}`
			if (choosedTimeStr == compareTimeStr) {
				this.compareTime = []
			}


			// 时间选择之后， 时间通译。
			this.choosedTimeStr = this.DateFilter(this.choosedTime)
			this.compareTimeStr = this.DateFilter(this.compareTime)


			this.updateChatsData()
		}, // 时间选择之后，做出对比，并且请求相关数据渲染图标
		DateFilter(value) {
			if (value.length === 0) {
				return '';
			} else if (value[0] == 'Invalid Date') {
				this.compareTime = []
				return '';
			}
			const today = new Date();
			today.setHours(0, 0, 0, 0); // 将时间部分设置为00:00:00

			const yesterday = new Date(today);
			yesterday.setDate(today.getDate() - 1);

			const sevenDaysAgo = new Date(today);
			sevenDaysAgo.setDate(today.getDate() - 6);

			const thirtyDaysAgo = new Date(today);
			thirtyDaysAgo.setDate(today.getDate() - 29);

			const nintyDaysAgo = new Date(today);
			nintyDaysAgo.setDate(today.getDate() - 89);


			const firstDate = new Date(value[0]);
			const secondDate = new Date(value[1]);
			let str = ''
			if (+firstDate === +secondDate) {
				if (this.formatDate(firstDate) === this.formatDate(today)) {
					str = '今天';
				} else if (this.formatDate(firstDate) === this.formatDate(yesterday)) {
					str = '昨天';
				} else {
					str = this.formatDate(firstDate);
				}
			} else {
				if (this.formatDate(secondDate) === this.formatDate(today)) {
					if (this.formatDate(firstDate) === this.formatDate(sevenDaysAgo)) {
						str = '过去7天';
					} else if (this.formatDate(firstDate) === this.formatDate(thirtyDaysAgo)) {
						str = '过去30天';
					} else if (this.formatDate(firstDate) === this.formatDate(nintyDaysAgo)) {
						str = '过去90天';
					} else {
						str = `${this.formatDate(firstDate)} - ${this.formatDate(secondDate)}`;
					}
				} else {
					str = `${this.formatDate(firstDate)} - ${this.formatDate(secondDate)}`;
				}
			}

			return str;
		},
		formatDate(date) {
			var d = new Date(date),
				month = '' + (d.getMonth() + 1),
				day = '' + d.getDate(),
				year = d.getFullYear();

			if (month.length < 2)
				month = '0' + month;
			if (day.length < 2)
				day = '0' + day;

			return [year, month, day].join('.');
		}
	}
})
app.use(ElementPlus);
app.mount("#seogtp_statistics_overview");
