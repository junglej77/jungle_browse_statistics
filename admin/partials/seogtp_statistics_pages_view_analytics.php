<div id="seogtp_statistics_pages_view_analytics">
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