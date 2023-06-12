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


import { createApp } from './vue'
// 新增代码：引入特定组件
// 此时会自动引入对应的样式文件，无需再手动逐一引入
import {
	ElButton
} from 'element-plus'

const app = createApp({
	data() {
		return {
			message: 'Hello Vue!',
			options: {
				title: {
					text: 'ECharts 入门示例'
				},
				tooltip: {},
				xAxis: {
					data: ['衬衫', '羊毛衫', '雪纺衫', '裤子', '高跟鞋', '袜子']
				},
				yAxis: {},
				series: [{
					name: '销量',
					type: 'bar',
					data: [5, 20, 36, 10, 10, 20]
				}]
			}
		}
	},
	mounted() {
		var myChart = echarts.init(document.getElementById('main'));
		myChart.setOption(this.options);
	},
	methods: {
	}
})
// 新增代码：注册特定组件
app.component(ElButton.name, ElButton)
app.mount('#seogtp_statistics_overview')

// 基于准备好的dom，初始化echarts实例
// 绘制图表

