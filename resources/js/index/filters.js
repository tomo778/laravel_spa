import moment from "moment";

/**
 * 指定文字数以上カット
 * @param {int} length
 * @param {String} omission
 * @returns {String}
 */
export function truncate(value, length, omission) {
    var length = length ? parseInt(length, 10) : 20;
    var ommision = omission ? omission.toString() : '...';

    if (value.length <= length) {
        return value;
    }

    return value.substring(0, length) + ommision;
}

/**
 * 日付時間フォーマット
 * @param {String} type
 * @returns {String}
 */
export function moment_format(value, type) {
    moment.locale("ja");
    switch (type) {
        case "LL":
            return moment(value).format("LL");
        case "LLL":
            return moment(value).format("LLL");
        default:
            return moment(value).format("LLLL");
    }
}