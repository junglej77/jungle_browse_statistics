<div id="seogtp_statistics_setup">
    设置时间差：<el-input width="200" v-model="input" placeholder="时间差"></el-input>
    <p style="color:red;">以下设置为了减少数据储存，节省服务器运行内存。减少网站打开速度</p>
    <div>
        <h1>浏览器</h1>
        <el-tag v-for="tag in Browsers" :key="tag" class="mx-1" closable :disable-transitions="false" @close="handleClose(tag)" type="success">
            {{ tag }}
        </el-tag>
        <el-input v-if="BrowsersInputRef" ref="BrowsersInputRef" v-model="inputValue" class="ml-1 w-20" size="small" @keyup.enter="handleInputConfirm('Browsers')" @blur="handleInputConfirm('Browsers')"></el-input>
        <el-button v-else class="button-new-tag ml-1" size="small" @click="showInput('Browsers')">
            + New Tag
        </el-button>
    </div>
    <div>
        <h1>社交媒体</h1>
        <el-tag v-for="tag in socials" :key="tag" class="mx-1" closable :disable-transitions="false" @close="handleClose(tag)" type="info">
            {{ tag }}
        </el-tag>
        <el-input v-if="socialsInputRef" ref="socialsInputRef" v-model="inputValue" class="ml-1 w-20" size="small" @keyup.enter="handleInputConfirm('socials')" @blur="handleInputConfirm('socials')"></el-input>
        <el-button v-else class="button-new-tag ml-1" size="small" @click="showInput('socials')">
            + New Tag
        </el-button>
    </div>
    <div>
        <h1>搜索引擎</h1>
        <el-tag v-for="tag in searchEgine" :key="tag" class="mx-1" closable :disable-transitions="false" @close="handleClose(tag)" type="warning">
            {{ tag }}
        </el-tag>
        <el-input v-if="searchEgineInputRef" ref="searchEgineInputRef" v-model="inputValue" class="ml-1 w-20" size="small" @keyup.enter="handleInputConfirm('searchEgine')" @blur="handleInputConfirm('searchEgine')"></el-input>
        <el-button v-else class="button-new-tag ml-1" size="small" @click="showInput('searchEgine')">
            + New Tag
        </el-button>
    </div>
    <div>
        <h1>国家</h1>
        <el-tag v-for="tag in country" :key="tag" class="mx-1" closable :disable-transitions="false" @close="handleClose(tag)">
            {{ tag }}
        </el-tag>
        <el-input v-if="countryInputRef" ref="countryInputRef" v-model="inputValue" class="ml-1 w-20" size="small" @keyup.enter="handleInputConfirm('country')" @blur="handleInputConfirm('country')"></el-input>
        <el-button v-else class="button-new-tag ml-1" size="small" @click="showInput('country')">
            + New Tag
        </el-button>
    </div>
</div>
<style>
    #wpcontent {
        padding: 20px;
    }

    .cell {
        text-align: center;
    }
</style>