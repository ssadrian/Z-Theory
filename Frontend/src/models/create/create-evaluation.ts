import { IRanking } from "../ranking.model";

export interface ICreateEvaluation {
    evaluator: number;
    subject: number;
    skill: number;
    ranking_id: number;
    skill_id: number;
    kudos: number;
}
