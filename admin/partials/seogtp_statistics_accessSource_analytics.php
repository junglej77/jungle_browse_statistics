<div id="seogtp_statistics_accessSource_analytics">
    <div class="head_wrap">

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