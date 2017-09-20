export default (yyyyMMdd) => {
	var date = new Date(Date.parse(yyyyMMdd + 'T00:00:00')).toDateString();

	return date.substring(0, date.length-5);
}