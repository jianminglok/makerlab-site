export default validate_required(value, error) {
  if (value === null || value === '') {
    return error;
  } else return true;
}
