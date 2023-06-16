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
import { ClickOutside as vClickOutside } from 'element-plus'
const app = Vue.createApp({
	data() {
		return {
			choosedTimeBtn: null,
			choosedTime: new Date(),
			compareTimeBtn: null,
			compareTime: '昨天',
			mdiCalendarMonth: mdiCalendarMonth,
			option3: {
				color: [
					"#ff9597",
					"#22cfe0",
					"#b9b1f0",
					"#51b7fb",
					"#76e68f",
					"#fa8b54",
					"#ffc545",
				],
				data: [
					{
						value: 899,
						name: "社交媒体",
					},
					{
						value: 151,
						name: "搜索引擎",
					},
					{
						value: 50,
						name: "直接访问",
					},
					{
						value: 40,
						name: "邮件营销",
					},
					{
						value: 10,
						name: "未知",
					},
				]
			},
			option4: {
				color: [
					"#ff9597",
					"#22cfe0",
					"#b9b1f0",
					"#51b7fb",
					"#76e68f",
					"#fa8b54",
					"#ffc545",
				],
				data: [
					{
						value: 580,
						name: "美国",
					},
					{
						value: 254,
						name: "法国",
					},
					{
						value: 249,
						name: "德国",
					},
					{
						value: 199,
						name: "意大利",
					},
					{
						value: 190,
						name: "英国",
					},
					{
						value: 38,
						name: "印度",
					},

				]
			},
			option5: {
				color: [
					"#ff9597",
					"#22cfe0",
					"#b9b1f0",
					"#51b7fb",
					"#76e68f",
					"#fa8b54",
					"#ffc545",
				],
				data: [
					{
						value: 580,
						name: "电脑",
					},
					{
						value: 254,
						name: "手机",
					},
					{
						value: 199,
						name: "平板",
					},
				]
			},
			option6: {
				color: [
					"#ff9597",
					"#22cfe0",
					"#b9b1f0",
					"#51b7fb",
					"#76e68f",
					"#fa8b54",
					"#ffc545",
				],
				data: [
					{
						value: 8939,
						name: "Facebook",
						ringRate: -21.5,
					},
					{
						value: 580,
						name: "Instagram",
						ringRate: 21.5,
					},
					{
						value: 477,
						name: "Youtube",
						ringRate: 0,
					},
					{
						value: 425,
						name: "Tiktok",
						ringRate: -21.5,
					},
					{
						value: 398,
						name: "LinkedIn",
						ringRate: -21.5,
					},
					{
						value: 254,
						name: "Twitter",
						ringRate: 21.5,
					},
					{
						value: 199,
						name: "Pinterest",
						ringRate: -21.5,
					},
					{
						value: 155,
						name: "Tumblr",
						ringRate: -21.5,
					},
					{
						value: 121,
						name: "Quora",
						ringRate: -21.5,
					},
					{
						value: 79,
						name: "Reddit",
						ringRate: 21.5,
					},
				]
			},
			option7: [
				{
					page: 'com.google.android.gm',
					value: 870
				},
				{
					page: 'www.dianxiaomi.com',
					value: 750
				},
				{
					page: 'https://pictogrammers.com/library/mdi/',
					value: 471
				},
				{
					page: 'https://tongji.baidu.com/main/overview/demo/overview/index?siteId=16847648',
					value: 170
				},
				{
					page: "https://www.deepl.com/translator#en/zh/We've%20delivered%20your%20parcel%20to%20a%20secure%20location%20at%20the%20delivery%20address",
					value: 5
				}
			],
			option8: [
				{
					page: '/',
					value: 1270
				},
				{
					page: '/about-weller',
					value: 970
				},
				{
					page: '/contact',
					value: 370
				},
				{
					page: '/wellerpcb_news',
					value: 72
				}
			],
			option8: [
				{
					page: '/',
					value: 20
				},
				{
					page: '/about-weller',
					value: 80
				},
				{
					page: '/contact',
					value: 500
				},
				{
					page: '/wellerpcb_news',
					value: 100
				}
			]
		}
	},
	mounted() {
		this.choosedTimeBtn = this.$refs.choosedTime
		this.compareTimeBtn = this.$refs.compareTime
		this.lineEchart(document.getElementById('line1'))
		this.barEchart1(document.getElementById('line2'))
		this.barEchart(document.getElementById('line3'), this.option3)
		this.barEchart(document.getElementById('line4'), this.option4)
		this.barEchart(document.getElementById('line5'), this.option5)
		this.barEchart(document.getElementById('line6'), this.option6)
	},
	methods: {
		cancelPopover() {
			this.$refs.choosedTimePopover.hide();
			this.$refs.compareTimePopover.hide();
		},
		barEchart(el, params) {
			let dataBase = params.data;

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
									console.log(a);
									let num = a.dataIndex + 1;
									let ringRate = dataBase[a.dataIndex].ringRate;
									let str = `{color1|${num}} {color2|${a.name}}`;
									if (ringRate) {
										str += ` {${ringRate >= 0 ? 'color4' : 'color3'}|${ringRate >= 0 ? '↑' : '↓'} ${Math.abs(ringRate)}}`
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

			var myChart = echarts.init(el);
			myChart.setOption(option);
		},
		barEchart1(el) {
			let dataNews = [];
			let dataOlds = [];
			let xData = (function () {
				var data = [];
				for (var i = 0; i < 24; i++) {
					data.push(i + '时');
					let x1 = Math.ceil(Math.random() * 1000);
					let x2 = Math.ceil(Math.random() * 1000);
					dataNews.push(x1);
					dataOlds.push(x2);
				}
				return data;
			})();
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
						data: xData,
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

			var myChart = echarts.init(el);
			myChart.setOption(option);
		},
		lineEchart(el) {
			let dataNews = [];
			let dataOlds = [];
			let xData = (function () {
				var data = [];
				for (var i = 0; i < 24; i++) {
					data.push(i + "时");
					let x1 = Math.ceil(Math.random() * 1000);
					let x2 = Math.ceil(Math.random() * 1000);
					dataNews.push(x1 + (i == 0 ? 0 : dataNews[i - 1]));
					dataOlds.push(x2 + (i == 0 ? 0 : dataOlds[i - 1]));
				}
				return data;
			})();
			let option = {
				grid: {
					top: 30,
					right: 0,
					bottom: 20,
				},
				tooltip: {
					trigger: "axis",
					axisPointer: {
						lineStyle: {
							color: "#ddd",
						},
					},
					backgroundColor: "rgba(255,255,255,1)",
					padding: [5, 10],
					textStyle: {
						color: "#7588E4",
					},
					extraCssText: "box-shadow: 0 0 5px rgba(0,0,0,0.3)",
				},
				legend: {
					right: 0,
					data: ["今天", "2023.06.11 - 2023.06.15"],
				},
				xAxis: {
					type: "category",
					data: xData,
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
				series: [
					{
						name: "今天",
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
					},
					{
						name: "2023.06.11 - 2023.06.15",
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
					},
				],
			};


			var myChart = echarts.init(el);
			myChart.setOption(option);
		},
		pieEchart(el, params) {
			const { color, data } = params
			let option = {
				color: color,
				tooltip: {
					trigger: "item",
					formatter: '{a} <br/>{b} : {c} ({d}%)'
				},
				legend: {
					bottom: 0,
					icon: "circle", //圆角矩形
					itemGap: 20,
					itemWidth: 16,
					itemHeight: 12,
					textStyle: {
						fontSize: 12,
					},
				},
				series: [
					{
						name: "项目",
						type: "pie",
						roseType: "radius",
						top: '-30%',
						center: ["50%", "50%"],
						radius: ["10%", "60%"],
						label: {
							show: true,
							position: "outside",
							formatter: function (params) {
								return `${params.name} \n ${params.value} \n( {b|${params.percent}%}) `;
							},
							rich: {
								b: {
									color: 'red',
								},
							},
							fontSize: 14,
						},
						data: data,
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: "rgba(0, 0, 0, 0.5)",
							},
						},
					},
				],
			};

			var myChart = echarts.init(el);
			myChart.setOption(option);

		}
	}
})
app.directive('click-outside', vClickOutside)

app.use(ElementPlus);
app.mount("#seogtp_statistics_overview");
