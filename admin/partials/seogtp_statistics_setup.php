<div id="seogtp_statistics_setup">
    <el-table ref="table" :data="tableData" row-key="id" :highlight-current-row="true" stripe>
        <el-table-column v-for="(item, key) in columns" :key="key" :prop="key" :class-name="key" v-bind="item">
            <template #header>
                <div class="title_intro">
                    <el-tooltip v-if="columns[key].headerIntro" class="box-item" effect="dark" :content="columns[key].headerIntro" placement="top-start">
                        <span>
                            {{ columns[key].label }}
                            <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" data-v-ea893728="">
                                <path fill="currentColor" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896zm0 192a58.432 58.432 0 0 0-58.24 63.744l23.36 256.384a35.072 35.072 0 0 0 69.76 0l23.296-256.384A58.432 58.432 0 0 0 512 256zm0 512a51.2 51.2 0 1 0 0-102.4 51.2 51.2 0 0 0 0 102.4z">
                                </path>
                            </svg>
                        </span>
                    </el-tooltip>
                    <span v-else>{{ columns[key].label }}</span>
                    <span v-if="columns[key].sortable" class="caret-wrapper">
                        <i class="sort-caret ascending"></i>
                        <i class="sort-caret descending"></i>
                    </span>
                </div>
                <div class="title_filter" v-if="columns[key].queryForm" @click.stop>
                    <!-- 搜索页面板块 -->
                    <el-input v-if="key == 'alias'" v-model.trim="queryForm.data.alias" placeholder="搜索板块" @change="getTableList" clearable></el-input>
                </div>
            </template>
        </el-table-column>
    </el-table>
</div>
<style>
    .el-table .el-table__cell {
        text-align: center;
    }
</style>