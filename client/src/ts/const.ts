export const BREAKPOINT_SP: number = 480;
export const BREAKPOINT_TAB: number = 768;
export const BREAKPOINT_PC: number = 1080;

export const ICON_IMAGE: string = `/images/icon.png`;
export const HEADER_TITLE_IMAGE: string = `/images/header_title.png`;
export const FOOTER_TITLE: string = '©分析ツール';
export const MAIN_MAIL_ADRESS = 'tachibana.yu31@gmail.com';

type ObjectForString = {
  [name: string]:  string
}

export const LINKS: ObjectForString[] = [
  {link: '/', text: 'ホーム'},
  {link: 'import', text: 'インポート'},
  {link: 'graph', text: 'グラフ'},
]

export const CONTENT_SUB_TITLES: ObjectForString = {
  profile: 'インポート',
  about: 'グラフ',
};

export const CONTENT_IMAGES: ObjectForString = {
  profile: '/images/profile.png',
  about: '/images/about.png',
  service: '/images/service.png'
}

export const DETAIL_PAGE_IMAGES: ObjectForString = {
  profile: '/images/profile_detail.png',
  about: '/images/about_detail.png',
  service: '/images/service_detail.png',
}

export const MAIL_OPTIONS: ObjectForString = {
  from: 'string',
  to: 'string',
  subject: 'string',
  text: 'string',
}

export const ERROR_MESSAGE: ObjectForString = {
  requireError: '※すべての項目を入力してください。',
  emailError: '※メールアドレスが無効な形式です。',
  notSameError: '※メールアドレスが確認用アドレスと同一でありません。',
}

export const MODAL_MESSAGE: ObjectForString = {
  sendSuccess: 'お問い合わせを受付けました。',
  sendError: `お問い合わせの受付に失敗しました。
    お手数をおかけしますが、以下のメールアドレスに直接お問い合わせ頂けると幸いです。
  `
}

export const LOG_MESSAGE: ObjectForString = {
  validationError: 'バリエーションエラーが発生しました。',
  requestSucsess: 'リクエストに成功しました。',
  requestFailed: 'リクエストに失敗しました。',
  requestError: 'エラーが発生しました。',
}

export const MONTHS = [
  '1月',
  '2月',
  '3月',
  '4月',
  '5月',
  '6月',
  '7月',
  '8月',
  '9月',
  '10月',
  '11月',
  '12月'
];
