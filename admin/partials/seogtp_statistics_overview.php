<div id="seogtp_statistics_overview">
    <div class="time_section_wrapper">
        <div class="compareTime_wrap time_section_wrap">
            <el-date-picker ref="choosedTime" popper-class="choosedTime_popover" v-model="choosedTime" type="daterange" unlink-panels :shortcuts="shortcuts" @change="compareDate" :disabled-date="disabledDate"></el-date-picker>
            <el-button @click="openDatePicker('choosedTime')">
                <svg style="width:24px" viewBox="0 0 24 24">
                    <path :d="mdiCalendarMonth"></path>
                </svg>
                {{DateFilter(choosedTime)}}
            </el-button>
        </div>
        <div class="compareTime_wrap time_section_wrap">
            <el-date-picker ref="compareTime" popper-class="compareTime_popover" v-model="compareTime" type="daterange" unlink-panels :shortcuts="shortcuts" @change="compareDate" :disabled-date="disabledDate"></el-date-picker>
            <el-button @click="openDatePicker('compareTime')">
                <span v-if="DateFilter(compareTime)">对比:</span>
                <span v-else>暂无对比</span>
                {{DateFilter(compareTime)?DateFilter(compareTime):''}}
            </el-button>
        </div>
    </div>

    <div class="database_overview">
        <el-row :gutter="20">
            <el-col :span="12">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">访客总计</h3>
                        <p class="dataline"></p>
                    </div>
                    <div class="echart_wrap ar16-7">
                        <div id="line1"></div>
                    </div>
                </div>
            </el-col>
            <el-col :span="12">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">新旧占比</h3>
                        <p class="dataline"></p>
                    </div>
                    <div class="echart_wrap ar16-7">
                        <div id="line2"></div>
                    </div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">访问来源</h3>
                        <p class="dataline"></p>
                    </div>
                    <div class="echart_wrap ar1-1">
                        <div id="line3"></div>
                    </div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">地域分布</h3>
                        <p class="dataline"></p>
                    </div>
                    <div class="echart_wrap ar1-1">
                        <div id="line4"></div>
                    </div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">访问设备</h3>
                        <p class="dataline"></p>
                    </div>
                    <div class="echart_wrap ar1-1">
                        <div id="line5"></div>
                    </div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">社媒来源</h3>
                        <p class="dataline"></p>
                    </div>
                    <div class="echart_wrap ar1-1">
                        <div id="line6"></div>
                    </div>
                </div>
            </el-col>
            <el-col :span="8">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">Top推荐页面</h3>
                        <p class="dataline"></p>
                    </div>
                    <div id="line7">
                        <el-table :data="option7" height="250" style="width: 100%">
                            <el-table-column prop="page" label="推荐域">
                                <template #default="scope">
                                    <a :href="scope.row.page">{{scope.row.page}}</a>
                                </template>
                            </el-table-column>
                            <el-table-column prop="value" label="推荐次数"></el-table-column>
                        </el-table>
                    </div>
                </div>
            </el-col>
            <el-col :span="8">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">Top受访页面</h3>
                        <p class="dataline"></p>
                    </div>
                    <div id="line8">
                        <el-table ref="table" :data="option8" row-key="id" height="250" style="width: 100%">
                            <el-table-column prop="page" label="访问页">
                                <template #default="scope">
                                    <a :href="scope.row.page">{{scope.row.page}}</a>
                                </template>
                            </el-table-column>
                            <el-table-column prop="value" label="查看次数"></el-table-column>
                        </el-table>
                    </div>
                </div>
            </el-col>
            <el-col :span="8">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">Top受访时长</h3>
                        <p class="dataline"></p>
                    </div>
                    <div id="line8">
                        <el-table ref="table" :data="option8" row-key="id" height="250" style="width: 100%">
                            <el-table-column prop="page" label="访问页">
                                <template #default="scope">
                                    <a :href="scope.row.page">{{scope.row.page}}</a>
                                </template>
                            </el-table-column>
                            <el-table-column prop="value" label="平均访问时长"></el-table-column>
                        </el-table>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</div>
<style>
    #wpcontent {
        padding: 20px;
    }
</style>