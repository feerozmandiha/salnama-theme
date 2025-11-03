(function() {
    'use strict';

    const { __ } = wp.i18n;
    const { 
        InspectorControls,
        useBlockProps 
    } = wp.blockEditor;
    const {
        PanelBody,
        SelectControl,
        RangeControl,
        ToggleControl,
        BaseControl
    } = wp.components;
    const { addFilter } = wp.hooks;

    // آپشن‌های انیمیشن
    const ANIMATION_OPTIONS = {
        entrance: [
            { label: '❌ بدون انیمیشن', value: 'none' },
            { label: '⬆️ ظاهر شدن از پایین', value: 'fade-up' },
            { label: '⬅️ ظاهر شدن از چپ', value: 'fade-left' },
            { label: '➡️ ظاهر شدن از راست', value: 'fade-right' },
            { label: '🔍 ظاهر شدن با scale', value: 'scale-in' },
            { label: '🌫️ ظاهر شدن با blur', value: 'blur-in' },
            { label: '🚀 اسلاید از پایین', value: 'slide-up' }
        ],
        hover: [
            { label: '❌ بدون انیمیشن', value: 'none' },
            { label: '📈 بلند شدن', value: 'lift' },
            { label: '✨ درخشش', value: 'glow' },
            { label: '💓 ضربان', value: 'pulse' },
            { label: '📳 تکان', value: 'shake' },
            { label: '🎯 کج شدن', value: 'tilt' }
        ],
        scroll: [
            { label: '❌ بدون انیمیشن', value: 'none' },
            { label: '🎬 پارالاکس', value: 'parallax' },
            { label: '📜 محو شدن هنگام اسکرول', value: 'fade-on-scroll' },
            { label: '📌 چسبنده', value: 'sticky' }
        ],
        duration: [
            { label: '⚡ سریع (0.3s)', value: 'fast' },
            { label: '🕒 معمولی (0.6s)', value: 'normal' },
            { label: '🐌 آهسته (1s)', value: 'slow' }
        ]
    };

    const AnimationControls = function({ attributes, setAttributes }) {
        const {
            animationEntrance = 'none',
            animationHover = 'none',
            animationScroll = 'none',
            animationDelay = 0,
            animationDuration = 'normal',
            animationStagger = false
        } = attributes;

        const hasAnyAnimation = animationEntrance !== 'none' || 
                               animationHover !== 'none' || 
                               animationScroll !== 'none';

        return React.createElement(
            InspectorControls,
            null,
            React.createElement(
                PanelBody,
                {
                    title: __('🎭 تنظیمات انیمیشن', 'salnama'),
                    initialOpen: false
                },
                hasAnyAnimation && React.createElement(
                    'div',
                    {
                        style: {
                            background: '#f0f9ff',
                            padding: '12px',
                            borderRadius: '4px',
                            marginBottom: '16px',
                            border: '1px solid #bae6fd'
                        }
                    },
                    React.createElement('strong', null, '✅ انیمیشن‌های فعال:'),
                    React.createElement('div', { 
                        style: { 
                            fontSize: '12px', 
                            marginTop: '4px' 
                        } 
                    },
                        animationEntrance !== 'none' && `ورود: ${animationEntrance} `,
                        animationHover !== 'none' && `هاور: ${animationHover} `,
                        animationScroll !== 'none' && `اسکرول: ${animationScroll}`
                    )
                ),
                React.createElement(SelectControl, {
                    label: __('انیمیشن ظاهر شدن', 'salnama'),
                    value: animationEntrance,
                    options: ANIMATION_OPTIONS.entrance,
                    onChange: function(value) { 
                        setAttributes({ animationEntrance: value }); 
                    }
                }),
                React.createElement(SelectControl, {
                    label: __('انیمیشن هاور موس', 'salnama'),
                    value: animationHover,
                    options: ANIMATION_OPTIONS.hover,
                    onChange: function(value) { 
                        setAttributes({ animationHover: value }); 
                    }
                }),
                React.createElement(SelectControl, {
                    label: __('انیمیشن اسکرول', 'salnama'),
                    value: animationScroll,
                    options: ANIMATION_OPTIONS.scroll,
                    onChange: function(value) { 
                        setAttributes({ animationScroll: value }); 
                    }
                }),
                (animationEntrance !== 'none' || animationHover !== 'none') && 
                React.createElement(
                    BaseControl,
                    {
                        label: __('تنظیمات زمان‌بندی', 'salnama'),
                        help: __('کنترل دقیق رفتار انیمیشن‌ها')
                    },
                    React.createElement(RangeControl, {
                        label: __('تأخیر انیمیشن (ms)', 'salnama'),
                        value: animationDelay,
                        onChange: function(value) { 
                            setAttributes({ animationDelay: value }); 
                        },
                        min: 0,
                        max: 2000,
                        step: 100,
                        withInputField: true
                    }),
                    React.createElement(SelectControl, {
                        label: __('مدت زمان انیمیشن', 'salnama'),
                        value: animationDuration,
                        options: ANIMATION_OPTIONS.duration,
                        onChange: function(value) { 
                            setAttributes({ animationDuration: value }); 
                        }
                    })
                ),
                (animationEntrance !== 'none' || animationHover !== 'none') && 
                React.createElement(ToggleControl, {
                    label: __('افکت آبشاری', 'salnama'),
                    checked: animationStagger,
                    onChange: function(value) { 
                        setAttributes({ animationStagger: value }); 
                    },
                    help: __('انیمیشن به ترتیب روی عناصر داخلی اجرا می‌شود')
                }),
                !hasAnyAnimation && React.createElement(
                    'div',
                    {
                        style: {
                            background: '#fefce8',
                            padding: '12px',
                            borderRadius: '4px',
                            fontSize: '12px',
                            border: '1px solid #fef08a'
                        }
                    },
                    React.createElement('strong', null, '💡 راهنما:'),
                    React.createElement('p', null, 'برای افزودن انیمیشن، از dropdownهای بالا استفاده کنید.')
                )
            )
        );
    };

    // ثبت کنترل برای تمام بلوک‌های پشتیبانی شده
    const withAnimationControls = function(BlockEdit) {
        return function(props) {
            const { name, attributes, setAttributes } = props;
            
            const supportedBlocks = [
                'core/group',
                'core/cover',
                'core/columns',
                'core/column', 
                'core/image',
                'core/media-text',
                'core/button',
                'core/gallery'
            ];

            if (supportedBlocks.includes(name)) {
                return React.createElement(
                    React.Fragment,
                    null,
                    React.createElement(BlockEdit, props),
                    React.createElement(AnimationControls, { 
                        attributes: attributes, 
                        setAttributes: setAttributes 
                    })
                );
            }

            return React.createElement(BlockEdit, props);
        };
    };

    // اضافه کردن فیلتر
    addFilter(
        'editor.BlockEdit',
        'salnama/animation-controls',
        withAnimationControls
    );

    // اضافه کردن کلاس‌ها در ادیتور برای پیش‌نمایش
    addFilter(
        'blocks.getSaveContent.extraProps',
        'salnama/addAnimationClasses',
        function(props, block, attributes) {
            var animationClasses = [];
            
            if (attributes.animationEntrance && attributes.animationEntrance !== 'none') {
                animationClasses.push('sal-' + attributes.animationEntrance);
            }
            
            if (attributes.animationHover && attributes.animationHover !== 'none') {
                animationClasses.push('sal-hover-' + attributes.animationHover);
            }
            
            if (attributes.animationScroll && attributes.animationScroll !== 'none') {
                animationClasses.push('sal-scroll-' + attributes.animationScroll);
            }

            if (animationClasses.length > 0) {
                props.className = props.className 
                    ? props.className + ' ' + animationClasses.join(' ') 
                    : animationClasses.join(' ');
            }

            return props;
        }
    );

})();