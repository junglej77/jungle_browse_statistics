<div id="seogtp_statistics_overview">
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

    <div class="database_overview">
        <el-row :gutter="20">
            <el-col :span="12">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title" v-cloak>
                            <span v-cloak>{{choosedTimeStr}}访客总计</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=visitor_analytics">查看详情</a>
                        </h3>
                        <p class="dataline" v-cloak>
                            {{echart1Fun().choosedDataTotal}}
                            <span v-if="compareTimeStr" :style="{color:echart1Fun().ringRate>0?'green':'red'}">
                                {{(echart1Fun().ringRate>0?'↑':'↓')+ echart1Fun().ringRate}}%
                            </span>
                        </p>
                    </div>
                    <div class="echart_wrap ar16-7">
                        <div id="line1"></div>
                    </div>
                </div>
            </el-col>
            <el-col :span="12">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">
                            <span>{{choosedTimeStr}}新旧访客占比</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=visitor_analytics">查看详情</a>
                        </h3>
                        <p class="dataline">
                            新访客：{{echart2Fun().choosedDataNewsTotal}}({{echart2Fun().choosedDataNewsTotalPercent}}%)
                            <span v-if="compareTimeStr" :style="{color:echart2Fun().choosedDataNewsTotalRingRate>0?'green':'red'}">
                                {{(echart2Fun().choosedDataNewsTotalRingRate>0?'↑':'↓')+ echart2Fun().choosedDataNewsTotalRingRate}}%
                            </span>
                            旧访客：{{echart2Fun().choosedDataOldsTotal}}({{echart2Fun().choosedDataOldsTotalPercent}}%)
                            <span v-if="compareTimeStr" :style="{color:echart2Fun().choosedDataOldsTotalRingRate>0?'green':'red'}">
                                {{(echart2Fun().choosedDataOldsTotalRingRate>0?'↑':'↓')+ echart2Fun().choosedDataOldsTotalRingRate}}%
                            </span>
                        </p>
                    </div>
                    <div class="echart_wrap ar16-7">
                        <div id="line2"></div>
                    </div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">
                            <span>访问来源</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=accessSource_analytics#refferPage">查看详情</a>
                        </h3>
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
                        <h3 class="title">
                            <span>地域分布</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=accessSource_analytics#country">查看详情</a>
                        </h3>
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
                        <h3 class="title">
                            <span>访问设备</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=accessSource_analytics#device">查看详情</a>
                        </h3>
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
                        <h3 class="title">
                            <span>社媒来源</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=accessSource_analytics#refferPage">查看详情</a>
                        </h3>
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
                        <h3 class="title">
                            <span>Top推荐页面</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=accessSource_analytics#refferPage">查看详情</a>
                        </h3>
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
                        <h3 class="title">
                            <span>Top受访页面</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=visitor_analytics">查看详情</a>
                        </h3>
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
                            <el-table-column prop="ringRate" label="同比">
                                <template #default="scope">
                                    <span :style="{color: scope.row.ringRate>0?'green':'red'}">{{scope.row.ringRate>0?'↑':'↓'}}{{Math.abs(scope.row.ringRate)}}%</span>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </el-col>
            <el-col :span="8">
                <div class="seogtp_statistics_database_card">
                    <div class="head_wrap">
                        <h3 class="title">
                            <span>Top受访时长</span>
                            <a class="view_more" href="/wp-admin/admin.php?page=visitor_analytics">查看详情</a>
                        </h3>
                        <p class="dataline"></p>
                    </div>
                    <div id="line8">
                        <el-table ref="table" :data="option9" row-key="id" height="250" style="width: 100%">
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
        position: relative;
        padding: 20px;
    }
</style>