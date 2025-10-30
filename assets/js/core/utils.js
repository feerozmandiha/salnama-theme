// assets/js/core/utils.js

/**
 * بررسی می کند که آیا صفحه در حالت دسکتاپ (عرض بالای ۱۰۲۴ پیکسل) قرار دارد یا خیر.
 * @returns {boolean}
 */
export const isDesktop = () => {
    return window.matchMedia('(min-width: 1025px)').matches;
};
