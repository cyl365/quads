<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('quick Search Placeholder', { fields: t('order.quick Search Fields') })"
            @action="baTable.onTableHeaderAction"
        />

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" @action="baTable.onTableAction" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, onMounted } from 'vue'
import baTableClass from '/@/utils/baTable'
import { order } from '/@/api/controllerUrls'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'

const { t } = useI18n()
const tableRef = ref()
const optButtons = defaultOptButtons(["edit","delete"])
const baTable = new baTableClass(
    new baTableApi(order),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('order.id'), prop: 'id', align: 'center', width: 70, sortable: 'custom', operator: 'RANGE' },
            { label: 'UID', prop: 'uid', align: 'center', operator: 'RANGE' },
            { label: t('order.user__nickname'), prop: 'user.nickname', align: 'center' },
            { label: t('order.user__mobile'), prop: 'user.mobile', align: 'center' },
            { label: t('order.goods_name'), prop: 'goods_name', align: 'center' },
            { label: t('order.num'), prop: 'num', align: 'center', operator: 'RANGE' },
            { label: t('order.order_money'), prop: 'order_money', align: 'center' },
            { label: t('order.pay_type'), prop: 'pay_type', align: 'center' },
            { label: t('order.status'), prop: 'status', align: 'center', render: 'tag', replaceValue: { 1: t('order.status 1'), 2: t('order.status 2'), 3: t('order.status 3') } },
            { label: t('order.createtime'), prop: 'createtime', align: 'center', render: 'datetime', sortable: 'custom', operator: 'RANGE', width: 160 },
            { label: t('operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined, ],
        defaultOrder: { prop: 'id', order: 'desc' },
    },
    {
        defaultItems: {"num":"1","status":"1","ordertimes":"120"},
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'order',
})
</script>

<style scoped lang="scss"></style>
