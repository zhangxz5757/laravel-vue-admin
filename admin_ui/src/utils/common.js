


export function TimeFormat(time) {
  let d = time ? new Date(time) : new Date(),
    obj = {
      year: d.getFullYear(),
      month: d.getMonth() + 1,
      day: d.getDate(),
      hours: d.getHours(),
      min: d.getMinutes(),
      seconds: d.getSeconds()
    }
  Object.keys(obj).forEach(key => {
    if (obj[key] < 10) obj[key] = `0${obj[key]}`
    // console.log(obj[key])
  })

  return `${obj.year}-${obj.month}-${obj.day} ${obj.hours}:${obj.min}:${obj.seconds}`
}
