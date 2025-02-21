-- Le Laboratoire Secret
UPDATE salles 
SET histoire = 'Dans les profondeurs d''un ancien complexe scientifique, le Dr. Marcus menait des expériences révolutionnaires sur la manipulation du temps. La veille de sa présentation à la communauté scientifique, il disparut mystérieusement, laissant derrière lui son laboratoire scellé et ses découvertes inachevées. Des témoins affirment avoir vu des lumières étranges et entendu des échos de voix traversant le temps... Saurez-vous percer les secrets du Dr. Marcus et peut-être même découvrir ce qui lui est arrivé ?'
WHERE nom = 'Le Laboratoire';

-- Le Manoir Hanté
UPDATE salles 
SET histoire = 'Construit en 1886 par le riche industriel Victor Blackwood, ce manoir fut le théâtre d''événements inexplicables. La famille Blackwood disparut mystérieusement lors d''une nuit d''orage en 1902, laissant le manoir abandonné. Les habitants du village racontent que les âmes des Blackwood hantent toujours les lieux, cherchant à révéler la vérité sur leur disparition. Des rires d''enfants résonnent parfois dans les couloirs vides, et le portrait de Madame Blackwood semble suivre les visiteurs du regard... Aurez-vous le courage de percer le mystère qui entoure la famille Blackwood ?'
WHERE nom = 'Le Manoir';

-- L''Île Perdue
UPDATE salles 
SET histoire = 'En 1952, une expédition scientifique découvrit une île qui n''apparaissait sur aucune carte. L''équipe y trouva les ruines d''une civilisation ancienne et avancée, mais disparut sans laisser de traces après avoir envoyé un dernier message radio : ''Ce que nous avons trouvé va changer l''histoire de l''humanité''. Soixante ans plus tard, vous avez retrouvé les coordonnées de l''île mystérieuse. Qu''est-il arrivé à l''expédition ? Quels secrets cette civilisation oubliée cache-t-elle ? À vous de le découvrir...'
WHERE nom = 'L''Île';

-- Le Train de l''Orient Express
UPDATE salles 
SET histoire = '1923, l''Orient Express, joyau des chemins de fer, relie Paris à Constantinople. Lors d''un voyage, un mystérieux vol de bijoux royaux se produit quelque part entre Vienne et Budapest. Le train est bloqué par la neige, et l''inspecteur principal a disparu. Vous avez une heure avant l''arrivée des autorités pour retrouver les bijoux et démasquer le coupable. Mais attention, le voleur est toujours à bord et ne vous laissera pas faire... Saurez-vous résoudre cette énigme digne d''Agatha Christie ?'
WHERE nom = 'Le Train';
