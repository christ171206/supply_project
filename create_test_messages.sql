-- Créer des messages de test pour la messagerie du vendeur
SET @testshop_id = (SELECT id FROM users WHERE email = 'testshop@supply.ci' LIMIT 1);
SET @client1 = (SELECT id FROM users WHERE role = 'client' LIMIT 1);
SET @client2 = (SELECT id FROM users WHERE role = 'client' LIMIT 1 OFFSET 1);
SET @client3 = (SELECT id FROM users WHERE role = 'client' LIMIT 1 OFFSET 2);

-- Messages du client 1
INSERT INTO messages (from_user_id, to_user_id, contenu, lu, created_at, updated_at)
VALUES 
(@client1, @testshop_id, "Bonjour, je suis intéressé par vos produits informatiques. Pouvez-vous me donner plus de détails?", 0, NOW(), NOW()),
(@testshop_id, @client1, "Bonjour! Merci pour votre intérêt. Je serais heureux de vous fournir tous les détails nécessaires sur nos produits. Pouvez-vous préciser lequel vous intéresse?", 1, NOW(), NOW()),
(@client1, @testshop_id, "Je suis intéressé par les ordinateurs portables de haut gamme avec RAM 16GB ou plus.", 0, NOW(), NOW());

-- Messages du client 2
INSERT INTO messages (from_user_id, to_user_id, contenu, lu, created_at, updated_at)
VALUES
(@client2, @testshop_id, "Quel est le délai de livraison pour les commandes?", 0, NOW(), NOW()),
(@testshop_id, @client2, "Bonjour! Les délais de livraison dépendent de votre localisation. En moyenne, c'est 3-5 jours ouverts pour Abidjan et 5-7 jours pour l'intérieur.", 1, NOW(), NOW());

-- Messages du client 3
INSERT INTO messages (from_user_id, to_user_id, contenu, lu, created_at, updated_at)
VALUES
(@client3, @testshop_id, "Avez-vous des produits en promotion en ce moment?", 0, NOW(), NOW());
