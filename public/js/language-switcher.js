(function () {
    'use strict';

    var languages = {
        en: { label: 'English', flag: '🇬🇧', dir: 'ltr' },
        ur: { label: 'اردو', flag: '🇵🇰', dir: 'rtl' },
        hi: { label: 'हिन्दी', flag: '🇮🇳', dir: 'ltr' },
        ar: { label: 'العربية', flag: '🇸🇦', dir: 'rtl' }
    };

    var translations = {
        ur: {
            'Home': 'ہوم', 'Features': 'خصوصیات', 'Supported Sites': 'معاون ویب سائٹس', 'FAQ': 'سوالات',
            'Download Videos from': 'ویڈیوز ڈاؤن لوڈ کریں', 'Any Website': 'کسی بھی ویب سائٹ سے',
            'Paste your video link below and download in high quality.': 'اپنا ویڈیو لنک نیچے پیسٹ کریں اور اعلیٰ معیار میں ڈاؤن لوڈ کریں۔',
            'Works with YouTube, Instagram, TikTok, Facebook and more!': 'یوٹیوب، انسٹاگرام، ٹک ٹاک، فیس بک اور مزید کے ساتھ کام کرتا ہے!',
            'Download': 'ڈاؤن لوڈ', 'Try these examples:': 'یہ مثالیں آزمائیں:', 'Super Fast': 'انتہائی تیز',
            'High Quality': 'اعلیٰ معیار', 'Multiple Formats': 'متعدد فارمیٹس', 'No Registration': 'رجسٹریشن نہیں',
            '100% Free': '100% مفت', 'Supported Platforms': 'معاون پلیٹ فارمز', 'Works with 100+ Popular Websites': '100 سے زیادہ مقبول ویب سائٹس کے ساتھ کام کرتا ہے',
            'Download in 3 Simple Steps': '3 آسان مراحل میں ڈاؤن لوڈ کریں', 'Paste Link': 'لنک پیسٹ کریں',
            'Choose Format': 'فارمیٹ منتخب کریں', 'Click Download': 'ڈاؤن لوڈ پر کلک کریں',
            'Paste your video link here...': 'اپنا ویڈیو لنک یہاں پیسٹ کریں...'
        },
        hi: {
            'Home': 'होम', 'Features': 'विशेषताएँ', 'Supported Sites': 'समर्थित साइटें', 'FAQ': 'सवाल',
            'Download Videos from': 'वीडियो डाउनलोड करें', 'Any Website': 'किसी भी वेबसाइट से',
            'Paste your video link below and download in high quality.': 'अपना वीडियो लिंक नीचे पेस्ट करें और उच्च गुणवत्ता में डाउनलोड करें।',
            'Works with YouTube, Instagram, TikTok, Facebook and more!': 'YouTube, Instagram, TikTok, Facebook और अन्य के साथ काम करता है!',
            'Download': 'डाउनलोड', 'Try these examples:': 'ये उदाहरण आज़माएँ:', 'Super Fast': 'बहुत तेज़',
            'High Quality': 'उच्च गुणवत्ता', 'Multiple Formats': 'कई फ़ॉर्मेट', 'No Registration': 'रजिस्ट्रेशन नहीं',
            '100% Free': '100% मुफ़्त', 'Works with 100+ Popular Websites': '100+ लोकप्रिय वेबसाइटों के साथ काम करता है',
            'Download in 3 Simple Steps': '3 आसान चरणों में डाउनलोड करें', 'Paste Link': 'लिंक पेस्ट करें',
            'Choose Format': 'फ़ॉर्मेट चुनें', 'Click Download': 'डाउनलोड क्लिक करें',
            'Paste your video link here...': 'अपना वीडियो लिंक यहाँ पेस्ट करें...'
        },
        ar: {
            'Home': 'الرئيسية', 'Features': 'المميزات', 'Supported Sites': 'المواقع المدعومة', 'FAQ': 'الأسئلة',
            'Download Videos from': 'حمّل الفيديوهات من', 'Any Website': 'أي موقع',
            'Paste your video link below and download in high quality.': 'ألصق رابط الفيديو أدناه وحمّله بجودة عالية.',
            'Works with YouTube, Instagram, TikTok, Facebook and more!': 'يعمل مع يوتيوب وإنستغرام وتيك توك وفيسبوك والمزيد!',
            'Download': 'تحميل', 'Try these examples:': 'جرّب هذه الأمثلة:', 'Super Fast': 'سريع جداً',
            'High Quality': 'جودة عالية', 'Multiple Formats': 'صيغ متعددة', 'No Registration': 'بدون تسجيل',
            '100% Free': 'مجاني 100%', 'Works with 100+ Popular Websites': 'يعمل مع أكثر من 100 موقع شهير',
            'Download in 3 Simple Steps': 'حمّل في 3 خطوات بسيطة', 'Paste Link': 'ألصق الرابط',
            'Choose Format': 'اختر الصيغة', 'Click Download': 'اضغط تحميل',
            'Paste your video link here...': 'ألصق رابط الفيديو هنا...'
        }
    };

    function normalize(value) { return value.replace(/\s+/g, ' ').trim(); }

    function rememberOriginals() {
        var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        var node;
        while ((node = walker.nextNode())) {
            if (normalize(node.nodeValue)) node.parentElement && (node.parentElement.dataset.originalText = node.parentElement.dataset.originalText || normalize(node.nodeValue));
        }
        document.querySelectorAll('input[placeholder]').forEach(function (input) {
            input.dataset.originalPlaceholder = input.placeholder;
        });
    }

    function translatePage(code) {
        var dictionary = translations[code] || {};
        document.documentElement.lang = code;
        document.documentElement.dir = languages[code].dir;
        document.querySelectorAll('[data-original-text]').forEach(function (element) {
            var original = element.dataset.originalText;
            if (element.childElementCount === 0) element.textContent = dictionary[original] || original;
        });
        document.querySelectorAll('[data-original-placeholder]').forEach(function (input) {
            input.placeholder = dictionary[input.dataset.originalPlaceholder] || input.dataset.originalPlaceholder;
        });
    }

    function buildSwitcher() {
        var oldButton = document.querySelector('.nav > .btn');
        if (!oldButton) return;
        var current = localStorage.getItem('saveFromsLanguage') || 'en';
        if (!languages[current]) current = 'en';
        var wrapper = document.createElement('div');
        wrapper.className = 'language-switcher';
        wrapper.innerHTML = '<button type="button" class="language-button" aria-expanded="false" aria-label="Choose language"><i class="bi bi-globe2"></i><span class="language-label"></span><i class="bi bi-chevron-down"></i></button><div class="language-menu" role="menu"></div>';
        oldButton.replaceWith(wrapper);
        var button = wrapper.querySelector('.language-button');
        var label = wrapper.querySelector('.language-label');
        var menu = wrapper.querySelector('.language-menu');

        function renderOptions() {
            label.textContent = languages[current].label;
            menu.innerHTML = Object.keys(languages).map(function (code) {
                var lang = languages[code];
                return '<button type="button" class="language-option' + (code === current ? ' active' : '') + '" data-language="' + code + '" role="menuitem"><span class="flag">' + lang.flag + '</span><span>' + lang.label + '</span>' + (code === current ? '<i class="bi bi-check2 check"></i>' : '') + '</button>';
            }).join('');
        }

        button.addEventListener('click', function () {
            var open = menu.classList.toggle('open');
            button.setAttribute('aria-expanded', String(open));
        });
        menu.addEventListener('click', function (event) {
            var option = event.target.closest('[data-language]');
            if (!option) return;
            current = option.dataset.language;
            localStorage.setItem('saveFromsLanguage', current);
            translatePage(current);
            renderOptions();
            menu.classList.remove('open');
            button.setAttribute('aria-expanded', 'false');
        });
        document.addEventListener('click', function (event) {
            if (!wrapper.contains(event.target)) { menu.classList.remove('open'); button.setAttribute('aria-expanded', 'false'); }
        });
        renderOptions();
        translatePage(current);
    }

    document.addEventListener('DOMContentLoaded', function () {
        rememberOriginals();
        buildSwitcher();
    });
}());
