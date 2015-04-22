select 'CF only' as 'set', count(article_id) as 'setsize' from 3971thesis_articles
where article_id in (select article_id from 3971thesis_membership where group_id = 2)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 3)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 8)
union all
select 'CAPT only', count(article_id) from 3971thesis_articles
where article_id in (select article_id from 3971thesis_membership where group_id = 3)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 2)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 8)
union all
select 'ELM only', count(article_id) from 3971thesis_articles
where article_id in (select article_id from 3971thesis_membership where group_id = 8)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 2)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 3)
union all
select 'CF n CAPT', count(article_id) from 3971thesis_articles
where article_id in (select article_id from 3971thesis_membership where group_id = 2)
	and article_id in (select article_id from 3971thesis_membership where group_id = 3)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 8)
union all
select 'CF n ELM', count(article_id) from 3971thesis_articles
where article_id in (select article_id from 3971thesis_membership where group_id = 2)
	and article_id in (select article_id from 3971thesis_membership where group_id = 8)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 3)
union all
select 'CAPT n ELM', count(article_id) from 3971thesis_articles
where article_id in (select article_id from 3971thesis_membership where group_id = 3)
	and article_id in (select article_id from 3971thesis_membership where group_id = 8)
	and article_id not in (select article_id from 3971thesis_membership where group_id = 2)
union all
select 'CF n CAPT n ELM', count(article_id) from 3971thesis_articles
where article_id in (select article_id from 3971thesis_membership where group_id = 2)
	and article_id in (select article_id from 3971thesis_membership where group_id = 3)
	and article_id in (select article_id from 3971thesis_membership where group_id = 8)
