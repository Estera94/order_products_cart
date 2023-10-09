import moment from "moment/moment";

export default function formattedDate(date) {
    return moment(date).format('ddd, MMM D');
}
