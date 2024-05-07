//リストなどの生成の際に使用するユニークキーを作成
export const generateUniqueKey = () => {
  const randomString = Math.random().toString(36).substring(2, 15);
  const timestamp = new Date().getTime();
  const uniqueString = `${randomString}_${timestamp}`;

  return uniqueString;
}

export const isSsl = () => {
  return document.location.protocol === 'https:';
}
