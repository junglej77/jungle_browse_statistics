<div id="seogtp_statistics_visitor_analytics">
    <el-tabs v-model="activeName" type="card" class="demo-tabs" tab-position="left" @tab-click="handleClick">
        <el-tab-pane label="User" name="first">User</el-tab-pane>
        <el-tab-pane label="Config" name="second">Config</el-tab-pane>
        <el-tab-pane label="Role" name="third">Role</el-tab-pane>
        <el-tab-pane label="Task" name="fourth">Task</el-tab-pane>
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