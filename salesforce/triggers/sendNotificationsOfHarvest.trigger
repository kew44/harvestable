trigger sendNotificationsOfHarvest on Harvest__c (after insert) {
    
    // query for the additional data you need in the trigger
        Map<Id, Harvest__c> accountMembersMap 
                    = new Map<Id, Harvest__c>([SELECT Id
                                                        , Account__r.Neighborhood__c
                                                        , Notify_Donation_Sites__c
                                                    FROM Harvest__c 
                                                    WHERE Id IN :Trigger.New]);

        for(Harvest__c triggerMember : Trigger.new){
            // retrieve the record from the map which has the related data
            
            Harvest__c queryMember = accountMembersMap.get(triggerMember.id);
           
            // do stuff using queryMember.Meeting__r.Name here
            if ((queryMember.Account__r.Neighborhood__c != '') && (queryMember.Notify_Donation_Sites__c == True)) {
                //hit the listener and pass in the neighborhood
                
             String harvestId= queryMember.Id;
                
             sendHarvestForAlerts.sendHarvest(harvestId);  

            }
            
     }
   
}