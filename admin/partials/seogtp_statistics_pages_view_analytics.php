<div id="seogtp_statistics_pages_view_analytics">
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
    <div class="query_form">
        <div style="display:inline-block;margin-right: 20px">
            <el-select v-model="singleDataCountry" multiple collapse-tags placeholder="全部国家" style="width: 240px" @change="val=>singleDataQuery('country',val)">
                <el-option v-for="item in randomCountries" :key="item.country" :label="item.country" :value="item.country" />
            </el-select>
        </div>
        <div style="display:inline-block;margin-right: 20px">
            <el-radio-group v-model="singleDataVisitorType" @change="val=>singleDataQuery('type',val)">
                <el-radio-button label="all">全部</el-radio-button>
                <el-radio-button label="new">新</el-radio-button>
                <el-radio-button label="old">老</el-radio-button>
            </el-radio-group>
            访客
        </div>
        <div style="display:inline-block;margin-right: 20px">
            <el-radio-group v-model="singleDataVisitorType" @change="val=>singleDataQuery('type',val)">
                <el-radio-button label="all">全部</el-radio-button>
                <el-radio-button label="all1">手机</el-radio-button>
                <el-radio-button label="new1">电脑</el-radio-button>
                <el-radio-button label="old1">平板</el-radio-button>
            </el-radio-group>
        </div>
        <div style="display:inline-block;margin-right: 20px">
            <el-select v-model="singleDataCountry" multiple collapse-tags placeholder="全部搜索引擎" style="width: 240px" @change="val=>singleDataQuery('country',val)">
                <el-option v-for="item in randomCountries" :key="item.country" :label="item.country" :value="item.country" />
            </el-select>
        </div>
    </div>
    <el-table ref="table" :data="tableData" row-key="id" :highlight-current-row="true" stripe>
        <el-table-column v-for="(item, key) in columns" :key="key" :prop="key" :class-name="key" v-bind="item">
            <template v-if="key == 'operation'" #default="scope">
                <el-button type="primary" text @click="handlelookPop(scope.row)">查看详情</el-button>
            </template>
        </el-table-column>
    </el-table>
    <el-dialog v-model="dialogFormVisible" @closed="handleDialogClose">
        <div slot="header" class="dialog-header">
            <h1>
                {{singleData.visitPage}}访问详情
            </h1>
        </div>
        <div class="query_form">
            <div style="margin-top: 20px">
                参照维度
                <el-radio-group v-model="radio3" size="small">
                    <el-radio-button label="流量来源"></el-radio-button>
                    <el-radio-button label="新老客户"></el-radio-button>
                    <el-radio-button label="国家"></el-radio-button>
                    <el-radio-button label="设备"></el-radio-button>
                    <el-radio-button label="浏览器"></el-radio-button>
                    <el-radio-button label="搜索引擎"></el-radio-button>
                </el-radio-group>
            </div>
        </div>
        <el-table ref="singleDataTableData" :data="singleDataTableData" row-key="id" :highlight-current-row="true" stripe>
            <el-table-column v-for="(item, key) in singleDataColumns" :key="key" :prop="key" :class-name="key" v-bind="item">
            </el-table-column>
        </el-table>
    </el-dialog>
</div>
<style>
    #wpcontent {
        padding: 20px;
    }

    .cell {
        text-align: center;
    }
</style>