<template>
    <div class="main-container">
        <section class="sidebar">
            <div class="sidebar-content">
                <header>
                    <a href="#"
                        ><div class="sidebar-logo-box">
                            <img src="~assets/logo.png" />
                            <h3>{{ siteConfig.site_name }}</h3>
                        </div>
                    </a>
                    <h1 class="sidebar-tagline">Build a blue sky</h1>
                </header>
                <div class="sidebar-banner">
                    <div class="sidebar-bg"></div>
                </div>
            </div>
        </section>
        <section class="content">
            <main>
                <div class="content-main">
                    <h2 class="content-title">{{ t('adminLogin.Sign in to') + " " + siteConfig.site_name }}</h2>

                    <el-divider class="content-hr">{{ t('adminLogin.Welcome') }}</el-divider>
                    <el-form @keyup.enter="onSubmit(formRef)" ref="formRef" :rules="rules" :model="form">
                        <!-- 账号 -->
                        <el-form-item prop="username">
                            <label class="content-label">{{ t('adminLogin.Please enter an account') }}</label>
                            <el-input v-model="form.username" clearable size="large" autofocus></el-input>
                        </el-form-item>

                        <!-- 密码 -->
                        <el-form-item prop="password">
                            <label class="content-label">{{ t('adminLogin.Please input a password') }}</label>
                            <el-input v-model="form.password" show-password size="large"></el-input>
                        </el-form-item>

                        <!-- 验证码 -->
                        <el-form-item v-if="state.showCaptcha" prop="captcha">
                            <label class="content-label">{{ t('adminLogin.Please enter the verification code') }}</label>
                            <div class="content-captcha">
                                <el-input
                                    ref="captchaRef"
                                    v-model="form.captcha"
                                    autocomplete="off"
                                    clearable
                                    size="large"
                                    type="text"
                                    class="content-captcha-input"
                                >
                                </el-input>
                                <img
                                    @click="onChangeCaptcha"
                                    class="content-captcha-img"
                                    :src="buildCaptchaUrl() + '&id=' + state.captchaId"
                                    alt=""
                                />
                            </div>
                        </el-form-item>

                        <!-- 保持会话-->
                        <el-checkbox v-model="form.keep" :label="t('adminLogin.Hold session')" class="content-remenber"></el-checkbox>

                        <!-- 登录 -->
                        <el-form-item>
                            <el-col :span="11">
                                <el-button type="primary" size="large" class="content-login-btn" @click="onSubmit(formRef)">{{
                                    t('adminLogin.Sign in')
                                }}</el-button>
                            </el-col>
                        </el-form-item>
                    </el-form>
                </div>
            </main>
        </section>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { uuid } from '/@/utils/random'
import { useSiteConfig } from '/@/stores/siteConfig'
import { buildCaptchaUrl } from '/@/api/common'
import { validatorPassword, validatorAccount } from '/@/utils/validate'
import { ElForm, ElInput, ElNotification } from 'element-plus'
import { login } from '/@/api/backend'
import { useAdminInfo } from '/@/stores/adminInfo'
import router from '/@/router'
import { useI18n } from 'vue-i18n'
import { index } from '/@/api/frontend/index'

const adminInfo = useAdminInfo()
const siteConfig = useSiteConfig()

const { t } = useI18n()

const state = reactive({
    showCaptcha: true,
    captchaId: uuid(),
})

const onChangeCaptcha = () => {
    form.captcha = ''
    state.captchaId = uuid()
}

const formRef = ref<InstanceType<typeof ElForm>>()
const captchaRef = ref<InstanceType<typeof ElInput>>()

const form = reactive({
    username: '',
    password: '',
    captcha: '',
    keep: false,
    captcha_id: '',
})

// 表单验证规则
const rules = reactive({
    username: [
        {
            required: true,
            message: t('adminLogin.Please enter an account'),
            trigger: 'blur',
        },
        {
            validator: validatorAccount,
            trigger: 'blur',
        },
    ],
    password: [
        {
            required: true,
            message: t('adminLogin.Please input a password'),
            trigger: 'blur',
        },
        {
            validator: validatorPassword,
            trigger: 'blur',
        },
    ],
    captcha: [
        {
            required: true,
            message: t('adminLogin.Please enter the verification code'),
            trigger: 'blur',
        },
        {
            min: 4,
            max: 6,
            message: t('adminLogin.The verification code length must be between 4 and 6 bits'),
            trigger: 'blur',
        },
    ],
})

const onSubmit = (formEl: InstanceType<typeof ElForm> | undefined) => {
    if (!formEl) return
    formEl.validate((valid) => {
        if (valid) {
            form.captcha_id = state.captchaId
            login('post', form)
                .then((res) => {
                    adminInfo.dataFill(res.data.userinfo)
                    ElNotification({
                        message: res.msg,
                        type: 'success',
                    })
                    router.push({ path: res.data.routePath })
                })
                .catch(() => {
                    onChangeCaptcha()
                })
        } else {
            onChangeCaptcha()
            return false
        }
    })
}

index()
</script>

<style scoped lang="scss">
* {
    margin: 0;
    padding: 0;
}

// 侧边栏
.sidebar {
    header {
        max-width: 416px;
        margin: 0 auto;
        padding: 48px 20px 0px;
        text-align: left;

        a {
            text-decoration: none;
            .sidebar-logo-box {
                display: none;
            }
        }

        .sidebar-tagline {
            display: none;
        }
    }

    .sidebar-banner {
        .sidebar-bg {
            background-image: url(/@/assets/build-sky-bg.jpg);
            flex-grow: 1;
            background-repeat: no-repeat;
            background-position: bottom center;
            background-size: cover;
        }
    }
}

.content {
    main {
        margin: 0 auto;
        padding: 0 20px;
        display: flex;

        .content-main {
            width: 100%;
            max-width: 416px;
            margin: auto;

            .content-title {
                margin: 35px 0 35px;
                font: bold 24px/29px 'Haas Grot Text R Web', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }

            .content-hr {
                margin-bottom: 17px;
                :deep(.el-divider__text) {
                    // #141414
                    background-color: var(--ba-bg-color);
                }
            }

            .content-label {
                color: var(--el-text-color-primary);
                margin-top: 20px;
                font: bold 14px/24px 'Haas Grot Text R Web', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }

            .content-remenber {
                margin-top: 20px;
            }

            .content-captcha {
                display: flex;
                width: 100%;
                justify-content: space-between;

                .content-captcha-input {
                    max-width: 60%;
                }

                img {
                    border-radius: 3px;
                    max-width: 36%;
                }
            }

            .el-button {
                width: 100%;
            }
        }
    }
}

@media only screen and (min-width: 960px) {
    .main-container {
        display: flex;
        flex-direction: row;
        height: 100%;
        overflow: hidden;

        .sidebar {
            width: 450px;
            background: #0085fe;
            color: var(--el-color-white);
            .sidebar-content {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                height: 100%;

                header {
                    padding: 60px 50px 30px 40px;
                    margin: 0;
                    max-width: 100%;

                    .sidebar-logo-box {
                        display: flex;
                        align-items: center;
                        img {
                            width: 25px;
                            height: auto;
                        }
                        h3 {
                            margin-left: 10px;
                            color: var(--el-color-white);
                        }
                    }

                    .sidebar-tagline {
                        display: block;
                        margin-top: 50px;
                        font: bold 32px/38px 'Haas Grot Text R Web', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                    }
                }

                .sidebar-banner {
                    display: flex;
                    flex-grow: 1;
                    justify-content: flex-end;
                    .sidebar-bg {
                        max-height: 100%;
                    }
                }
            }
        }

        .content {
            display: flex;
            flex: 1;
            flex-direction: column;
            overflow: auto;

            main {
                display: flex;
                flex-grow: 1;
                align-content: center;
                justify-content: center;
                align-items: center;
                margin: 0;
                padding: 0;

                .content-main {
                    margin: 0;
                    padding: 30px 30px 0;

                    .content-title {
                        margin: 0 0 70px;
                    }

                    .content-hr {
                        margin-bottom: 38px;
                    }

                    .content-login-btn {
                        margin-top: 10px;
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 1100px) {
    .sidebar {
        width: 514px !important;
    }
}

// 暗黑样式
@at-root .dark {
    .sidebar {
        filter: brightness(70%);
    }
}
</style>
