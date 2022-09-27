<template>
    <!-- 对话框表单 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="baTable.form.operate ? true : false"
        @close="baTable.toggleForm"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                <FormItem :label="t('order.uid')" type="number" prop="uid" v-model.number="baTable.form.items!.uid" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('order.uid') }) }" />
                <FormItem :label="t('order.goods_name')" type="string" v-model="baTable.form.items!.goods_name" prop="goods_name" :input-attr="{ placeholder: t('Please input field', { field: t('order.goods_name') }) }" />
                <FormItem :label="t('order.money')" type="number" prop="money" v-model.number="baTable.form.items!.money" :input-attr="{ step: '0.01', placeholder: t('Please input field', { field: t('order.money') }) }" />
                <FormItem :label="t('order.num')" type="number" prop="num" v-model.number="baTable.form.items!.num" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('order.num') }) }" />
                <FormItem :label="t('order.order_money')" type="string" v-model="baTable.form.items!.order_money" prop="order_money" :input-attr="{ placeholder: t('Please input field', { field: t('order.order_money') }) }" />
                <FormItem :label="t('order.pay_type')" type="string" v-model="baTable.form.items!.pay_type" prop="pay_type" :input-attr="{ placeholder: t('Please input field', { field: t('order.pay_type') }) }" />
                <FormItem :label="t('order.mark')" type="string" v-model="baTable.form.items!.mark" prop="mark" :input-attr="{ placeholder: t('Please input field', { field: t('order.mark') }) }" />
                <FormItem :label="t('order.status')" type="radio" v-model="baTable.form.items!.status" prop="status" :data="{ content: { 1: t('order.status 1'), 2: t('order.status 2'), 3: t('order.status 3') } }" :input-attr="{ placeholder: t('Please select field', { field: t('order.status') }) }" />
                <FormItem :label="t('order.ordertimes')" type="number" prop="ordertimes" v-model.number="baTable.form.items!.ordertimes" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('order.ordertimes') }) }" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, inject } from 'vue'
import { useI18n } from 'vue-i18n'
import type baTableClass from '/@/utils/baTable'
import FormItem from '/@/components/formItem/index.vue'
import type { ElForm, FormItemRule } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'


const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    uid: [buildValidatorData('required', t('order.uid'))],
    goods_name: [buildValidatorData('required', t('order.goods_name'))],
    money: [buildValidatorData('required', t('order.money'))],
    num: [buildValidatorData('required', t('order.num'))],
    order_money: [buildValidatorData('required', t('order.order_money'))],
    pay_type: [buildValidatorData('required', t('order.pay_type'))],
    status: [buildValidatorData('required', t('order.status'))],
    ordertimes: [buildValidatorData('required', t('order.ordertimes'))],
})

</script>

<style scoped lang="scss"></style>
