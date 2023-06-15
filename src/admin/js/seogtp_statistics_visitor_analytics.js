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

// 注册必须的组件
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

const app = Vue.createApp({
	data() {
		return {
		}
	},
	mounted() {
		this.chartinitial1(document.getElementById('line1'))
		this.chartinitial1(document.getElementById('line2'))
		this.chartinitial1(document.getElementById('line3'))
		this.chartinitial1(document.getElementById('line4'))
	},
	methods: {
		chartinitial1(el) {
			let xData = (function () {
				var data = [];
				for (var i = 1; i < 13; i++) {
					data.push(i + "月份");
				}
				return data;
			})();

			let option = {
				backgroundColor: "#344b58",
				title: {
					textStyle: {
						color: "#fff",
						fontSize: "22",
					},
					subtextStyle: {
						color: "#90979c",
						fontSize: "16",
					},
				},
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
					borderWidth: 0,
					top: 40,
					bottom: 35,
					textStyle: {
						color: "#fff",
					},
				},
				legend: {
					right: 50,
					top: 10,
					textStyle: {
						color: "#90979c",
					},
					data: ["新", "老"],
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
							interval: 0,
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
				dataZoom: [
					{
						show: true,
						height: 10,
						xAxisIndex: [0],
						bottom: 5,
						start: 0,
						end: 100,
						handleIcon:
							"path://M306.1,413c0,2.2-1.8,4-4,4h-59.8c-2.2,0-4-1.8-4-4V200.8c0-2.2,1.8-4,4-4h59.8c2.2,0,4,1.8,4,4V413z",
						handleStyle: {
							color: "#d3dee5",
						},
						textStyle: {
							color: "#fff",
						},
						borderColor: "#90979c",
					},
					{
						type: "inside",
						show: true,
						height: 15,
						start: 1,
						end: 35,
					},
				],
				series: [
					{
						name: "新",
						type: "bar",
						stack: "总量",
						barMaxWidth: 35,
						barGap: "10%",
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
						data: [
							709, 1917, 2455, 2610, 1719, 1433, 1544, 3285, 5208, 3372, 2484, 4078,
						],
					},
					{
						name: "老",
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
						data: [327, 1776, 507, 1200, 800, 482, 204, 1390, 1001, 951, 381, 220],
					},
				],
			};

			var myChart = echarts.init(el);
			myChart.setOption(option);
		}
	}
})

app.use(ElementPlus);
app.mount("#seogtp_statistics_online");