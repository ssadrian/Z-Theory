export interface ISkill {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
  pivot: IPivot;
}

interface IPivot {
	student_id: number;
	skill_id: number;
	ranking_id: number;
	kudos: number;
	image?: any;
}
