export default (amount) => {
	var money = new Intl.NumberFormat('en-US', { 
    minimumFractionDigits: 2,
    maximumFractionDigits: 2 
  }).format(amount);
  
  return '$' + money;
}