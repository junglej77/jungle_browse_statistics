<div id="seogtp_statistics_visitor_analytics">
    <div class="time_section_wrapper">
        <div class="compareTime_wrap time_section_wrap">
            <el-date-picker ref="choosedTime" popper-class="choosedTime_popover" v-model="choosedTime" type="daterange" unlink-panels :shortcuts="shortcuts" @change="value=>compareDate(value,'choosedTime')" :disabled-date="disabledDate"></el-date-picker>
            <el-button @click="openDatePicker('choosedTime')" v-cloak>
                <svg style="width:24px" viewBox="0 0 24 24">
                    <path :d="mdiCalendarMonth"></path>
                </svg>
                {{choosedTimeStr}}
            </el-button>
        </div>
        <div class="compareTime_wrap time_section_wrap">

            <el-date-picker ref="compareTime" popper-class="compareTime_popover" v-model="compareTime" type="daterange" unlink-panels :shortcuts="shortcuts" @change="value=>compareDate(value,'compareTime')" :disabled-date="disabledDate"></el-date-picker>

            <el-button @click="openDatePicker('compareTime')" v-cloak>
                <span v-if="compareTimeStr">对比:</span>
                <span v-else>暂无对比</span>
                {{compareTimeStr}}
            </el-button>
        </div>
    </div>
    <div class="head_wrap">
        访客： {{tableData.length}}人
        在线人数： {{tableData.filter(item => item.status == '在线').length}}人
        <!-- <el-tabs v-model="activeName" type="card" class="demo-tabs" tab-position="left" @tab-click="handleClick">
            <el-tab-pane label="新旧占比" name="first">新旧占比</el-tab-pane>
            <el-tab-pane label="访问次数" name="second">访问次数</el-tab-pane>
            <el-tab-pane label="访问时长" name="third">访问时长</el-tab-pane>
            <el-tab-pane label="跳出率" name="fourth">跳出率</el-tab-pane>
        </el-tabs> -->
    </div>

    <el-table ref="table" :data="tableData" row-key="id" :highlight-current-row="true" stripe>
        <el-table-column type="expand">
            <template #default="props">
                <el-descriptions class="margin-top" :column="2" :size="size" border>
                    <el-descriptions-item>
                        <p> 访客类型：{{props.row.visitCount>1?'老访客':'新访客'}}</p>
                        <p> 首次来源：{{props.row.firstIndexSource}}</p>
                        <p> 首次访问时间：{{props.row.firstIndexTime}}</p>
                        <p> 总访问时长：{{props.row.totalTimeLength}}</p>
                        <p> 总访问天数：{{props.row.totalVisitDayCount}}</p>
                        <p> 总访问次数：{{props.row.totalVisitCount}}</p>
                        <p> 总访问页面：{{props.row.totalVisitPageNum}}</p>
                        <p> 总跳出次数：{{props.row.totalVisitBounceRate}}</p>
                    </el-descriptions-item>
                    <el-descriptions-item>
                        <el-timeline>
                            <el-timeline-item v-for="log in props.row.AccessLog" :timestamp="log.time" placement="top">
                                <h4>{{log.action}}</h4>
                            </el-timeline-item>
                        </el-timeline>
                    </el-descriptions-item>
                </el-descriptions>
            </template>
        </el-table-column>
        <el-table-column v-for="(item, key) in columns" :key="key" :prop="key" :class-name="key" v-bind="item">
        </el-table-column>
    </el-table>
</div>
<style>
    #wpcontent {
        padding: 20px;
    }

    .cell {
        text-align: center;
    }
</style>