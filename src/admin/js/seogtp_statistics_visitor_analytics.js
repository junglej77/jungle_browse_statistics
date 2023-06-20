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
				imgUrl: {
					label: "第一次来源",
				},
				alias: {
					label: "本次来源",
				},
				title: {
					label: "国家",
				},
				title: {
					label: "城市",
				},
				title: {
					label: "州/省",
				},
				description: {
					label: "设备",
				},
				linkTo: {
					label: "跳转链接",
				},
			},
			tableData: [
				{
					imgUrl: 'asd',
					alias: 'asd',
					title: 'asdc',
					description: '12312',
					linkTo: '123qweqw',
				}
			],
		}
	},
	mounted() {
		// this.initialChatsData()
	},
	methods: {
		handleClick() {
			console.log(tab, event)
		}
	}
})
app.use(ElementPlus);
app.mount("#seogtp_statistics_visitor_analytics");
