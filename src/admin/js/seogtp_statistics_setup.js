const app = Vue.createApp({
    data() {
        return {
            input: 15,
            Browsers: ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'],
            socials: [
                "Facebook",
                "Instagram",
                "Twitter",
                "LinkedIn",
                "Snapchat",
                "Pinterest",
                "Reddit",
                "TikTok",
                "YouTube",
                "微信",
                "QQ",
                "WhatsApp",
                "Messenger",
                "Viber",
                "Sina Weibo",
                "QZone",
                "Tumblr",
                "Telegram",
                "Line",
                "VKontakte"
            ],
            searchEgine: [
                "谷歌",
                "Bing",
                "Yahoo",
                "百度",
                "Yandex",
                "DuckDuckGo",
                "Ask.com",
                "AOL.com",
                "WolframAlpha",
                "Internet Archive",
                "ChaCha.com",
                "Alhea",
                "MyWebSearch",
                "WebCrawler",
                "Infospace",
                "Info.com",
                "Contenko",
                "Dogpile",
                "Lycos",
                "Excite"
            ],
            country: [
                "美国",
                "中国",
                "日本",
                "德国",
                "印度",
                "英国",
                "法国",
                "意大利",
                "巴西",
                "加拿大",
                "俄罗斯",
                "韩国",
                "西班牙",
                "澳大利亚",
                "墨西哥",
                "印度尼西亚",
                "荷兰",
                "沙特阿拉伯",
                "土耳其",
                "瑞士",
                "波兰",
                "泰国",
                "瑞典",
                "比利时",
                "伊朗",
                "奥地利",
                "挪威",
                "阿拉伯联合酋长国",
                "尼日利亚",
                "以色列",
                "南非",
                "爱尔兰",
                "丹麦",
                "新加坡",
                "马来西亚",
                "哥伦比亚",
                "菲律宾",
                "巴基斯坦",
                "智利",
                "芬兰",
                "孟加拉国",
                "埃及",
                "越南",
                "葡萄牙",
                "捷克",
                "罗马尼亚",
                "新西兰",
                "希腊",
                "伊拉克"
            ],
            BrowsersInputRef: false,
            socialsInputRef: false,
            searchEgineInputRef: false,
            countryInputRef: false,
            inputValue: '',
        }
    },
    mounted() {
    },
    methods: {
        handleClose(tag) {
            this.Browsers.splice(this.Browsers.indexOf(tag), 1)
        },
        showInput(type) {
            if (type == 'Browsers') {
                this.BrowsersInputRef = true
            } else if (type == 'socials') {
                this.socialsInputRef = true
            } else if (type == 'searchEgine') {
                this.searchEgineInputRef = true
            } else if (type == 'country') {
                this.countryInputRef = true
            }
            this.$nextTick(() => {
                this.$refs.BrowsersInputRef.focus()
                this.$refs.socialsInputRef.focus()
                this.$refs.searchEgineInputRef.focus()
                this.$refs.countryInputRef.focus()
            })
        },
        handleInputConfirm(type) {
            if (this.inputValue) {
                if (type == 'Browsers') {
                    this.Browsers.push(this.inputValue)
                } else if (type == 'socials') {
                    this.socials.push(this.inputValue)
                } else if (type == 'searchEgine') {
                    this.searchEgine.push(this.inputValue)
                } else if (type == 'country') {
                    this.country.push(this.inputValue)
                }
            }
            this.BrowsersInputRef = false
            this.socialsInputRef = false
            this.searchEgineInputRef = false
            this.countryInputRef = false
            this.inputValue = ''
        }
    }
})
app.use(ElementPlus);
app.mount("#seogtp_statistics_setup");
