<template>
  <div class="divBox">
    <el-row>
      <el-col>
        <el-card class="block_card">
          <h3>服务器信息</h3>
          <el-table :data="serverData">
            <el-table-column prop="hostname" label="服务器名称"/>
            <el-table-column prop="os" label="操作系统"/>
            <el-table-column prop="kernel" label="内核版本"/>
            <el-table-column prop="up_time" label="启动时间"/>
            <el-table-column prop="framework" label="系统架构"/>
<!--            <el-table-column prop="load" label="系统当前负载">-->
<!--              <template v-slot="scope">-->
<!--                {{scope.row.load}}%-->
<!--              </template>-->
<!--            </el-table-column>-->
<!--            <el-table-column prop="cpu" label="CPU核数"/>-->
          </el-table>
        </el-card>
      </el-col>
    </el-row>

    <el-row :gutter="12" class="mt10">
      <el-col :span="12">
        <el-card class="block_card">
          <h3>CPU</h3>
          <el-table :data="cpuData">
            <el-table-column prop="label" label="属性"/>
            <el-table-column prop="value" label="值"/>
          </el-table>
        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card class="block_card">
          <h3>内存</h3>
          <el-table :data="memData">
            <el-table-column prop="label" label="属性"/>
            <el-table-column prop="value" label="值"/>
          </el-table>
        </el-card>
      </el-col>
    </el-row>

    <el-row class="mt10">
      <el-col>
        <el-card class="block_card">
          <h3>磁盘状态</h3>
          <el-table :data="diskData">
            <el-table-column prop="device" label="设备"/>
            <el-table-column prop="mount" label="挂载点"/>
            <el-table-column prop="size" label="总容量">
              <template v-slot="scope">
                {{(scope.row.size / 1024 / 1024 / 1024).toFixed(2)}}GB
              </template>
            </el-table-column>
            <el-table-column prop="used" label="已使用">
              <template v-slot="scope">
                {{(scope.row.used / 1024 / 1024 / 1024).toFixed(2)}}GB
              </template>
            </el-table-column>
            <el-table-column prop="used_percent" label="已用比例">
              <template v-slot="scope">
                {{scope.row.used_percent}}%
              </template>
            </el-table-column>
            <el-table-column prop="free" label="剩余容量">
              <template v-slot="scope">
                {{(scope.row.free / 1024 / 1024 / 1024).toFixed(2)}}GB
              </template>
            </el-table-column>
            <el-table-column prop="free_percent" label="剩余比例">
              <template v-slot="scope">
                {{scope.row.free_percent}}%
              </template>
            </el-table-column>
          </el-table>
        </el-card>
      </el-col>
    </el-row>

  </div>
</template>
<script>

import {serverInfo} from "@/api/monitor/monotor";

export default {
  name: "ServerIndex",
  components: {},

  data() {
    return {
      cpuData: [],
      memData: [],
      serverData: [], // 服务器信息
      diskData: [], // 磁盘使用情况
    }
  },

  created() {
    // 查询列表数据
    this.getServerInfo()
  },

  methods: {

    getServerInfo() {
      serverInfo().then(res => {
        this.serverData.push(res.data.sys)
        this.diskData = res.data.disk
        // CPU data
        let cpuData = res.data.cpu
        this.cpuData.push({label: "核心数", value: cpuData.num || "unknow"})
        this.cpuData.push({label: "当前负载", value: cpuData.load_now + "%" || "unknow"})
        this.cpuData.push({label: "5分钟负载%", value: cpuData.load_5 + "%"  || "unknow"})
        this.cpuData.push({label: "15分钟负载%", value: cpuData.load_15 + "%"  || "unknow"})

        // mem
        let memData = res.data.mem
        let swapUse = memData.swapTotal - memData.swapFree
        let memUse = memData.total - memData.free
        this.memData.push({label: "总内存", value: (memData.total / 1024 / 1024 / 1024).toFixed(2) + "GB" || "unknow"})
        this.memData.push({label: "已用内存", value: ((memUse - swapUse) / 1024 / 1024 / 1024).toFixed(2) + "GB" || "unknow"})
        this.memData.push({label: "闲置物理内存", value: (memData.free / 1024 / 1024 / 1024).toFixed(2) + "GB" || "unknow"})
        this.memData.push({label: "内存使用率", value: ((memUse - swapUse) * 100 / memData.total).toFixed(2) + "%"})
      })
    },
  }
}
</script>

<style>

.block_card {
  padding: 0 14px 2px;
  background: #ffffff;
  border-radius: 4px;
}

</style>
