<div id="seogtp_statistics_accessSource_analytics">
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
    <el-tabs v-model="tabLocation" type="card" class="demo-tabs" @tab-click="handleClick">
        <el-tab-pane label="推荐页" name="refferPage"></el-tab-pane>
        <el-tab-pane label="国家" name="country"></el-tab-pane>
        <el-tab-pane label="搜索关键词" name="searchKeyword"></el-tab-pane>
        <el-tab-pane label="设备" name="device"></el-tab-pane>
        <el-tab-pane label="浏览器" name="browser"></el-tab-pane>
    </el-tabs>
    <el-table ref="table" :data="tableData" row-key="id" :highlight-current-row="true" stripe>
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