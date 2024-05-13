export interface LogsHome {
  today: Today | null;
  groupThisMonths: GroupThisMonths[];
}

export interface LogsGraph {
  groupMonths: GroupMonths;
}

export interface GroupMonths {
  datas: {
    month: string | null;
    count: number | null;
  }[];
  maxCount: number;
}

export interface Today {
  count: number;
}

export interface GroupThisMonths {
  format_date: string;
  count: number;
}
