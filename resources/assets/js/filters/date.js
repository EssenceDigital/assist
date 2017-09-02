export default (yyyyMMdd) => {
	return new Date(Date.parse(yyyyMMdd + 'T00:00:00')).toDateString();
}