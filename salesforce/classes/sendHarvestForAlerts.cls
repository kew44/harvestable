global class sendHarvestForAlerts{
    @future (callout=true) 
    public static void sendHarvest(String harvestId) {          
        // create an HTTPrequest object    
        HttpRequest req = new HttpRequest();
        // set up the HTTP request with a method, endpoint, header, and body
        req.setMethod('POST');
        req.setEndpoint('https://secure-earth-8932.herokuapp.com/listener.php');
        req.setHeader('Content-Type', 'application/json');
        req.setBody(harvestId);    
        // create a new HTTP object
        Http http = new Http();
        // create a new HTTP response for receiving the remote response
        // then use it to send the configured HTTPrequest
        HTTPResponse res = http.send(req);
        // debugging call, which you can check in debug logs
        System.debug('Fulfillment service returned '+ res.getBody());
    }
}