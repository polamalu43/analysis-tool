import Table from 'react-bootstrap/Table';
import { GroupThisMonths } from '../../../type';
import { generateUniqueKey } from '../../../utility';

type Props = {
  groupThisMonths: GroupThisMonths[];
}

const GropuThisMonth: React.FC<Props> = ({ groupThisMonths }) => {
  console.log(groupThisMonths);
  return (
    <>
      <Table striped bordered hover>
        <thead>
          <tr>
            <th>日付</th>
            <th>アクセス数</th>
          </tr>
        </thead>
        <tbody>
          {groupThisMonths.map((groupThisMonth) => (
            <tr key={generateUniqueKey()}>
              <td>{groupThisMonth.format_date}</td>
              <td>{groupThisMonth.count}</td>
            </tr>
          ))}
        </tbody>
      </Table>
    </>
  );
}

export default GropuThisMonth;
