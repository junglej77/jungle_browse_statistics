<div id="seogtp_statistics_visitor_analytics">
    <div class="head_wrap">
        在线人数： 397人
        <el-tabs v-model="activeName" type="card" class="demo-tabs" tab-position="left" @tab-click="handleClick">
            <el-tab-pane label="新旧占比" name="first">新旧占比</el-tab-pane>
            <el-tab-pane label="访问次数" name="second">访问次数</el-tab-pane>
            <el-tab-pane label="访问时长" name="third">访问时长</el-tab-pane>
            <el-tab-pane label="跳出率" name="fourth">跳出率</el-tab-pane>
        </el-tabs>
    </div>

    <el-table ref="table" :data="tableData" row-key="id" :highlight-current-row="true" stripe>
        <el-table-column type="expand">
            <template #default="props">
                <el-descriptions class="margin-top" :column="3" :size="size" border>
                    <el-descriptions-item>
                        <template #label>
                            <div class="cell-item">
                                新老访客
                            </div>
                        </template>
                        {{props.row.visitCount>1?'老访客':'新访客'}}
                    </el-descriptions-item>
                    <el-descriptions-item>
                        <template #label>
                            <div class="cell-item">
                                Place
                            </div>
                        </template>
                        Suzhou
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